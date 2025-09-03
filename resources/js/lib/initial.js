export function useInitials(routes) {
    const loadLocaleMessages  = async () => {
        const response = await fetch(`${baseUrl}/locale.json`);
        if (!response.ok) {
            throw new Error(`Failed to load ${locale} locale`);
        }
        return await response.json();
    };

    const loadBackendRoutes = async () => {
        const response = await fetch(`${baseUrl}/routes.json`);
        if (!response.ok) {
            throw new Error(`Failed to load routes`);
        }
        return await response.json();
    };

    const loanInitialJson = async () => {
        const response = await fetch(`${baseUrl}/load.json`);
        if (!response.ok) {
            throw new Error(`Failed to load ${locale} locale`);
        }
        return await response.json();
    };

    const mapRoutes = (routes) => {
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
                newRoute.children = mapRoutes(route.children);
            }
            return newRoute;
        });
    };

    return {
        loadLocaleMessages,
        loadBackendRoutes,
        loanInitialJson,
        mapRoutes,
    }
}
