<?php

namespace Softworx\RocXolid\Services;

use Illuminate\Contracts\Routing\Registrar as RegistrarContract;

class RouteService
{
    /**
     * Route reference.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param RegistrarContract $router Router reference to access current controller.
     * @return \Softworx\RocXolid\Services\RouteService
     */
    public function __construct(RegistrarContract $router)
    {
        $this->router = $router;
    }

    /**
     * Get attached router.
     *
     * @return \Illuminate\Contracts\Routing\Registrar
     */
    public function getRouter(): RegistrarContract
    {
        return $this->router;
    }

    /**
     * Return route for current controller and given action.
     *
     * @param string $action Route action.
     * @param array $params Route parameters.
     * @return string
     */
    public function getRoute(string $action, array $params = null): string
    {
        /*
        list($controller, $method) = explode('@', $this->getRouter()->currentRouteAction());

        $path = explode('\\', strtolower(str_replace('App\Http\Controllers\\', '', $controller)));
        $path[] = str_replace('controller', '', array_pop($path));;
        $path[] = $action;

        return !is_null($params) ? route(implode('.', $path), $params) : route(implode('.', $path));
        */
    }

    /**
     * Get current controller's action.
     *
     * @return string|null
     */
    public function getMethod(): ?string
    {
        if ($this->getRouter()->currentRouteAction()) {
            list($controller, $method) = explode('@', $this->getRouter()->currentRouteAction());
        } else {
            return null;
        }

        return $method;
    }
}
