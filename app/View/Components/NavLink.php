<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Request;

class NavLink extends Component
{
    public $href;
    public $route;
    public $routes;
    public $activeClass;
    public $inactiveClass;
    public $isActive;

    /**
     * Create a new component instance.
     *
     * @param string $href
     * @param string|null $route
     * @param array|null $routes
     * @param string $activeClass
     * @param string $inactiveClass
     */
    public function __construct(
        $href = '#',
        $route = null,
        $routes = null,
        $activeClass = 'text-blue-600 font-semibold',
        $inactiveClass = 'text-gray-500'
    ) {
        $this->href = $href;
        $this->route = $route;
        $this->routes = $routes ? (is_array($routes) ? $routes : [$routes]) : null;
        $this->activeClass = $activeClass;
        $this->inactiveClass = $inactiveClass;

        $this->isActive = $this->checkIfActive();
    }

    /**
     * Check if the current route is active
     *
     * @return bool
     */
    private function checkIfActive()
    {
        // If routes array is provided, check against all routes
        if ($this->routes) {
            return request()->routeIs($this->routes);
        }

        // If single route is provided, check against it
        if ($this->route) {
            return request()->routeIs($this->route);
        }

        // Fallback: check if current URL matches href
        return request()->url() === $this->href;
    }

    /**
     * Get the CSS classes for the link
     *
     * @return string
     */
    public function getClasses()
    {
        return $this->isActive ? $this->activeClass : $this->inactiveClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.nav-link');
    }
}
