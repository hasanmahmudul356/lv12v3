export function mapBackendRoutes(routes) {
    return routes.map(route => {
        const newRoute = {
            path: route.path,
            name: route.name,
            meta: route.meta || {},
        };
        if (route.component) {
            newRoute.component = () => import(`/resources/js/${route.component}`);
        }
        if (route.alias) {
            newRoute.alias = route.alias;
        }
        if (route.children && route.children.length > 0) {
            newRoute.children = mapBackendRoutes(route.children);
        }
        return newRoute;
    });
}
