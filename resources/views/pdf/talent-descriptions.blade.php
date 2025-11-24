<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подробные описания ваших талантов</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #6366f1;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 10px 0;
        }
        
        .header p {
            color: #6b7280;
            margin: 5px 0;
            font-size: 14px;
        }
        
        .talent-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .talent-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 15px;
        }
        
        .talent-rank {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            margin-right: 15px;
        }
        
        .talent-info {
            flex: 1;
        }
        
        .talent-name {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 5px 0;
        }
        
        .talent-meta {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .talent-domain {
            font-size: 12px;
            color: #6b7280;
        }
        
        .talent-percentage {
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .talent-description {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .description-text {
            color: #374151;
            line-height: 1.7;
            text-align: justify;
        }
        
        .talent-advice {
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        
        .advice-text {
            color: #374151;
            line-height: 1.7;
            text-align: justify;
        }
        
        .advice-text ul {
            margin: 10px 0;
            padding-left: 0;
            list-style-type: none;
        }
        
        .advice-text li {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dotted #e5e7eb;
        }
        
        .advice-text li:last-child {
            border-bottom: none;
        }
        
        .advice-text li strong {
            display: block;
            margin-bottom: 5px;
            color: #4b5563;
            font-size: 13px;
        }
        
        .advice-text li p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 11px;
        }
        
        /* Domain colors */
        .domain-executing { background-color: #702B7C; }
        .domain-influencing { background-color: #DA782D; }
        .domain-relationship { background-color: #316EC6; }
        .domain-strategic { background-color: #429162; }
        
        /* Page break */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Топ 10 талантов - подробные описания</h1>
        <p>Персональный отчет по результатам тестирования</p>
        <p>Дата создания: {{ now()->format('d.m.Y H:i') }}</p>
        @if($testDate)
            <p>Дата прохождения теста: {{ $testDate }}</p>
        @endif
    </div>

    @foreach($userResults as $index => $talent)
        @if(!empty($talent['description']))
            @php
                $percentage = round(($talent['score'] / $maxScore) * 100, 1);
                $domainColor = $domainColors[$talent['domain']] ?? '#6B7280';
                
                $domainClass = '';
                switch($talent['domain']) {
                    case 'executing':
                        $domainClass = 'domain-executing';
                        break;
                    case 'influencing':
                        $domainClass = 'domain-influencing';
                        break;
                    case 'relationship':
                        $domainClass = 'domain-relationship';
                        break;
                    case 'strategic':
                        $domainClass = 'domain-strategic';
                        break;
                    default:
                        $domainClass = 'domain-executing';
                }
            @endphp
            
            <div class="talent-card">
                <div class="talent-header">
                    <div class="talent-rank {{ $domainClass }}">
                        {{ $talent['rank'] }}
                    </div>
                    <div class="talent-info">
                        <h3 class="talent-name">{{ $talent['name'] }}</h3>
                        <div class="talent-meta">
                            <span class="talent-domain">{{ $domains[$talent['domain']] ?? '' }}</span>
                            <span class="talent-percentage" style="background-color: {{ $domainColor }}20; color: {{ $domainColor }};">
                                {{ $percentage }}%
                            </span>
                        </div>
                    </div>
                </div>

                <div class="talent-description">
                    <div class="section-title">Обзор таланта {{ $talent['name'] }}</div>
                    <div class="description-text">{{ $talent['description'] }}</div>
                </div>

                <div class="talent-advice">
                    <div class="section-title">Советы для человека с талантом {{ $talent['name'] }}</div>
                    <div class="advice-text">
                        @if(isset($talentAdvice[$talent['name']]) && is_array(json_decode($talentAdvice[$talent['name']], true)))
                            @php
                                $adviceItems = json_decode($talentAdvice[$talent['name']], true);
                            @endphp
                            <ul>
                                @foreach($adviceItems as $item)
                                    <li>
                                        <strong>{{ $item['name'] }}</strong>
                                        <p>{{ $item['description'] }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            {!! $talentAdvice[$talent['name']] ?? '' !!}
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Добавляем разрыв страницы после каждых 2 талантов для лучшего форматирования --}}
            @if(($index + 1) % 2 == 0 && $index + 1 < count($userResults))
                <div class="page-break"></div>
            @endif
        @endif
    @endforeach

    <div class="footer">
        <p>Этот отчет сгенерирован автоматически на основе ваших результатов тестирования талантов.</p>
        <p>© {{ date('Y') }} ProfGid - Профессиональная ориентация</p>
    </div>
</body>
</html>
