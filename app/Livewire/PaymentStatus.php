<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TestSession;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentStatus extends Component
{
    public $sessionId;
    public $plan;
    public $testSession;
    public $pin1;
    public $pin2;
    public $pin3;
    public $pin4;
    public $pin5;
    public $pin6;
    public $paymentConfirmed = false;
    public $halfDiscount = false;
    public $showPromoCodeForm = false;
    public $promoCodeGlobal = '';
    public $promoCodeGlobalPercent = 0;

    public function processCardPayment()
    {
        try {
            // Prepare order data
            $orderData = [
                'order' => [
                    'typeRid' => 'Order_RID',
                    'language' => 'ru',
                    'amount' => number_format($this->plans[$this->plan]['price'], 2, '.', ''),
                    'currency' => 'KZT',
                    'hppRedirectUrl' => route('payment-status', ['sessionId' => $this->sessionId, 'plan' => $this->plan]),
                    'description' => $this->plans[$this->plan]['name'],
                    'title' => 'PROFGID'
                ]
            ];

            // Log the request data for debugging
            Log::info('ForteBank API Request:', ['data' => $orderData]);

            // Make API request to ForteBank
            $response = Http::timeout(30)
                ->withBasicAuth('TerminalSys/PROFGID28000410', 'PROFGID28000410@0118')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post('https://api.fortebank.com/order', $orderData);

            // Log the response for debugging
            Log::info('ForteBank API Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['order']['id']) && isset($responseData['order']['password'])) {
                    $id = $responseData['order']['id'];
                    $password = $responseData['order']['password'];

                    // Save order ID and password to the database
                    $this->testSession->update([
                        'order_id' => $id,
                        'order_password' => $password,
                        'payment_status' => 'processing'
                    ]);

                    // Generate payment URL
                    $paymentUrl = "https://ecom.fortebank.com/flex/?id={$id}&password={$password}";

                    // Log successful payment URL generation
                    Log::info('Payment URL generated:', [
                        'url' => $paymentUrl,
                        'order_id' => $id,
                        'session_id' => $this->sessionId
                    ]);

                    // Redirect to payment page
                    return redirect()->away($paymentUrl);
                } else {
                    Log::error('ForteBank API: Invalid response structure', ['response' => $responseData]);
                    session()->flash('error', 'Ошибка при создании заказа. Попробуйте еще раз.');
                }
            } else {
                Log::error('ForteBank API: Request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                session()->flash('error', 'Ошибка соединения с банком. Код ошибки: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('ForteBank API Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Произошла ошибка при обработке платежа: ' . $e->getMessage());
        }
    }

    public $plans = [
        "talents" => [
            "name" => "Мои Таланты",
            "price" => 18990,
            "currency" => "тг",
        ],
        "talents_spheres" => [
            "name" => "Мои Профессии",
            "price" => 18990,
            "currency" => "тг",
        ],
        "talents_spheres_professions" => [
            "name" => "Мои таланты + Профессии",
            "price" => 18990,
            "currency" => "тг",
        ],
    ];

    // Removed static array - now using database

    protected $listeners = ["refreshPaymentStatus"];

    public function mount($sessionId, $plan)
    {
        $this->sessionId = $sessionId;
        $this->plan = $plan;

        $this->testSession = TestSession::where(
            "session_id",
            $this->sessionId
        )->first();

        if (!$this->testSession) {
            session()->flash("error", "Сессия теста не найдена");
            return redirect()->route("payment");
        }

        // Check payment status with ForteBank if we have order details and status is processing
        if ($this->testSession->order_id && $this->testSession->order_password && $this->testSession->payment_status === 'processing') {
            $this->checkPaymentStatusWithForteBank();
            $this->testSession = $this->testSession->fresh(); // Refresh after status check
        }

        // Проверяем, подтверждена ли уже оплата
        $this->paymentConfirmed =
            $this->testSession->payment_status === "completed";
    }

    public function refreshPaymentStatus()
    {
        $this->testSession = $this->testSession->fresh();

        // If we have order_id and order_password, check payment status with ForteBank
        if ($this->testSession->order_id && $this->testSession->order_password && $this->testSession->payment_status === 'processing') {
            $this->checkPaymentStatusWithForteBank();
        }

        $this->paymentConfirmed = $this->testSession->payment_status === "completed";
    }

    public function checkPaymentStatusWithForteBank()
    {
        try {
            $orderId = $this->testSession->order_id;
            $orderPassword = $this->testSession->order_password;

            // Make API request to check payment status
            $response = Http::timeout(30)
                ->withBasicAuth('TerminalSys/OMAROVA28000381', 'OMAROVA28000381@1498')
                ->get("https://api.fortebank.com/order/{$orderId}", [
                    'password' => $orderPassword,
                    'tranDetailLevel' => 1
                ]);

            Log::info('ForteBank Status Check Response:', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'body' => $response->body(),
                'session_id' => $this->sessionId
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['order']['status'])) {
                    $forteStatus = $responseData['order']['status'];

                    // Map ForteBank status to our payment status
                    $paymentStatus = $this->mapForteStatusToPaymentStatus($forteStatus);

                    // Update test session with new status
                    $this->testSession->update([
                        'payment_status' => $paymentStatus
                    ]);

                    Log::info('Payment status updated:', [
                        'order_id' => $orderId,
                        'forte_status' => $forteStatus,
                        'payment_status' => $paymentStatus,
                        'session_id' => $this->sessionId
                    ]);

                    // Show success message if payment is completed
                    if ($paymentStatus === 'completed') {
                        session()->flash('success', 'Оплата успешно подтверждена!');
                    }
                }
            } else {
                Log::error('ForteBank Status Check Failed:', [
                    'order_id' => $orderId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('ForteBank Status Check Exception:', [
                'order_id' => $this->testSession->order_id ?? 'N/A',
                'message' => $e->getMessage(),
                'session_id' => $this->sessionId
            ]);
        }
    }

    private function mapForteStatusToPaymentStatus($forteStatus)
    {
        switch ($forteStatus) {
            case 'Preparing':
                return 'processing';
            case 'FullyPaid':
                return 'completed';
            case 'PartPaid':
                return 'review';
            case 'Authorized':
                return 'review';
            case 'Declined':
            case 'Refused':
            case 'Rejected':
                return 'failed';
            case 'Expired':
                return 'failed';
            case 'Refunded':
                return 'refunded';
            case 'Voided':
            case 'Cancelled':
                return 'cancelled';
            case 'Closed':
                return 'completed';
            case 'WaitPushTran':
                return 'processing';
            case 'Funded':
                return 'completed';
            default:
                return 'pending';
        }
    }

    public function getPromoCode()
    {
        $this->showPromoCodeForm = true;

        // Prepare WhatsApp link but don't redirect
        $phone = "+77072170555";
        $message = urlencode(
            "Здравствуйте! Хочу получить промокод для профориентационного теста."
        );
        $whatsappUrl = "https://wa.me/$phone?text=$message";

        // Emit event to open WhatsApp in new tab via JavaScript
        $this->dispatch("openWhatsApp", $whatsappUrl);
    }

    public function confirmPayment()
    {
        // Validate PIN inputs
        $this->validate(
            [
                "pin1" => "required|string|size:1",
                "pin2" => "required|string|size:1",
                "pin3" => "required|string|size:1",
                "pin4" => "required|string|size:1",
                "pin5" => "required|string|size:1",
                "pin6" => "required|string|size:1",
            ],
            [
                "pin*.required" => "Пожалуйста, заполните все поля промокода",
                "pin*.size" => "Каждое поле должно содержать ровно один символ",
            ]
        );

        // Combine PIN digits to form the promo code
        $promoCode =
            $this->pin1 .
            $this->pin2 .
            $this->pin3 .
            $this->pin4 .
            $this->pin5 .
            $this->pin6;

        $this->promoCodeGlobal = $promoCode;

        // Find the promo code in database
        $promoCodeRecord = PromoCode::query()->where("code", $promoCode)
            ->where("is_active", true)
            ->where("is_used", false)
            ->first();

        $this->promoCodeGlobalPercent = $promoCodeRecord->discount;
        if ($promoCodeRecord) {
            if ($promoCodeRecord->discount > 0 && $promoCodeRecord->discount < 100) {
                $this->plans[$this->plan]['price'] = $this->plans[$this->plan]['price'] - ($this->plans[$this->plan]['price'] * $promoCodeRecord->discount) / 100;

                $this->testSession->update([
                    "promo_code" => $promoCode,
                    'payment_amount' => $this->plans[$this->plan]['price'],
                ]);

                $this->halfDiscount = true;

                if ($promoCodeRecord->unlimited_uses === 0) {
                    $promoCodeRecord->markAsUsed(auth()->id());
                }

//                session()->flash(
//                    "halfDiscount",
//                    "Промокод {$promoCodeRecord->code} активирован! Скидка применена успешно."
//                );

                return;
            }

            // Mark promo code as used
            if ($promoCodeRecord->unlimited_uses === 0) {
                $promoCodeRecord->markAsUsed(auth()->id());
            }

            // Update test session as completed
            $this->testSession->update([
                "payment_status" => "completed",
                "promo_code" => $promoCode,
            ]);

            $this->paymentConfirmed = true;

            session()->flash(
                "success",
                "Промокод принят! Оплата успешно подтверждена."
            );
        } else {
            session()->flash(
                "error",
                "Неверный промокод или промокод уже использован. Пожалуйста, проверьте правильность ввода."
            );
        }
    }

    public function getStatusText()
    {
        switch ($this->testSession->payment_status) {
            case "pending":
                return "Ожидание оплаты";
            case "processing":
                return "Обработка оплаты";
            case "review":
                return "Проверка оплаты";
            case "completed":
                return "Оплата подтверждена";
            case "failed":
                return "Ошибка оплаты";
            case "refunded":
                return "Возврат средств";
            case "cancelled":
                return "Отменено";
            default:
                return "Неизвестный статус";
        }
    }

    public function getStatusColor()
    {
        switch ($this->testSession->payment_status) {
            case "pending":
                return "text-yellow-600 bg-yellow-100";
            case "processing":
                return "text-blue-600 bg-blue-100";
            case "review":
                return "text-blue-600 bg-blue-100";
            case "completed":
                return "text-green-600 bg-green-100";
            case "failed":
                return "text-red-600 bg-red-100";
            case "refunded":
                return "text-orange-600 bg-orange-100";
            case "cancelled":
                return "text-gray-600 bg-gray-100";
            default:
                return "text-gray-600 bg-gray-100";
        }
    }

    public function render()
    {
        return view("livewire.payment-status");
    }
}
