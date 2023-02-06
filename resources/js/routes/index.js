import Vue from 'vue';
import VueRouter from "vue-router";

// Container
import TheContainer from '../containers/TheContainer';

import Dashboard from "../views/Dashboard";
import Page404 from "../views/Pages/ErrorPages/Page404";
import Login from "../views/Pages/Login";
import Products from "../views/Products/Products";
import Product from "../views/Products/Product";
import CronStatus from "../views/CronStatus";
import FbaInbound from "../views/FbaInbound";
import FbaInboundDetails from "../views/FbaInboundDetails";
import Inventory from "../views/Products/Inventory";
import Fulfillments from "../views/Fulfillments/Fulfillments";
import CreateFulfillment from "../views/Fulfillments/CreateFulfillment";
import Page500 from "../views/Pages/ErrorPages/Page500";
import ShippingLabelsLog from "../views/Logs/ShippingLabelsLog";
import FreightLabels from "../views/Freight/FreightLabels";
import SkuMappings from "../views/Products/SkuMappings";
import api from "../api";

Vue.use(VueRouter);

const guard = function(to, from, next) {
    // check for valid auth token
    api.get('auth/checkAuthToken').then(response => {
        // Token is valid, so continue
        next();
    }).catch(error => {
        next('login');
    })
};

const routes = [
    {
        path: "/login",
        name: 'Login',
        component: Login,
    },
    {
        path: '/',
        redirect: '/fulfillments',
        name: 'layout',
        component: TheContainer,
        beforeEnter: (to, from, next) => {
            guard(to, from, next);
        },
        children: [
            {
                path: '/dashboard',
                name: 'Dashboard',
                component: Dashboard
            },
            {
                path: '/products',
                name: 'Products',
                component: Products,
                meta: {
                    breadCrumb: 'Products'
                },
            },
            {
                path: '/products/inventory',
                name: 'Inventory',
                component: Inventory,
                meta: {
                    breadCrumb: 'Products/Inventory'
                }
            },
            {
                path: '/products/sku/:sku',
                name: 'Product',
                component: Product,
                props: true,
                meta: {
                    breadCrumb: 'Products/sku/:sku'
                }
            },
            {
                path: '/fulfillments',
                name: 'Fulfillment\'s',
                component: Fulfillments,
                meta: {
                    breadCrumb: 'Fulfillments'
                },
            },
            {
                path: '/fulfillments/create',
                name: 'New Fulfillment',
                component: CreateFulfillment,
                meta: {
                    breadCrumb: 'Fulfillment\'s/New'
                },
            },
            {
                path: '/fulfillments/inbound',
                name: 'FbaInbound',
                component: FbaInbound,
                props: true,
                meta: {
                    breadCrumb: 'FbaInbound'
                }
            },
            {
                path: '/fulfillments/inbound/:shipmentId',
                name: 'FbaInboundDetails',
                component: FbaInboundDetails,
                props: true,
                meta: {
                    breadCrumb: 'FbaInboundDetails/:shipmentId'
                }
            },
            {
                path: '/freight/new',
                name: 'Create Freight Label',
                component: FreightLabels,
                meta: {
                    breadCrumb: 'freight/new'
                },
            },
            {
                path: '/products/skumappings',
                name: 'Sku Mappings',
                component: SkuMappings,
                meta: {
                    breadCrumb: 'products/sku mappings'
                },
            },
            {
                path: '/services/logs/shipping_labels',
                name: 'Shipping Labels Log',
                component: ShippingLabelsLog,
                meta: {
                    breadCrumb: 'Logs/Shipping Labels'
                },
            },
            {
                path: '/services/cronstatus',
                name: 'CronStatus',
                component: CronStatus,
                props: true,
                meta: {
                    breadCrumb: 'Cronstatus'
                }
            },
            {
                path: '/admin',
                name: 'CronStatus',
                component: CronStatus,
                props: true,
                meta: {
                    breadCrumb: 'Cronstatus'
                }
            },
        ],
    },
    {
        path: '/404',
        name: 'System Error',
        component: Page404
    },
    {
        path: '/500',
        name: 'System Error',
        component: Page500
    },
    {
        path: "*",
        component: Page404
    },
];

const Router = new VueRouter({
    mode: 'hash',
    routes,
});

export default Router;
