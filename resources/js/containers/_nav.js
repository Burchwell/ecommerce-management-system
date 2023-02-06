export default [{
    _name: 'SideBarNav',
    _children: [
        {
            _type: 'SidebarNavDropdownItem',
            name: 'Home',
            icon: 'fas fa-home',
            to: '/',
            children: [
                {
                    _type: 'SidebarNavItem',
                    name: 'Dashboard',
                    to: '/dashboard',
                    icon: 'fas fa-chart-line',
                }
            ]
        },
        {
            _type: 'SidebarNavDropdownItem',
            name: 'Products',
            icon: 'fas fa-pallet',
            to: '/products',
            children: [
                {
                    _type: 'SidebarNavItem',
                    name: 'Products',
                    icon: 'fas fa-pallet',
                    to: '/products',

                },
                {
                    _type: 'SidebarNavItem',
                    name: 'Inventory',
                    icon: 'fas fa-boxes',
                    to: '/products/inventory',
                },
                {
                    _type: 'SidebarNavItem',
                    name: 'Sku Mappings',
                    icon: 'fad fa-layer-group',
                    to: '/products/skumappings'
                },
            ]
        },
        {
            _type: 'SidebarNavDropdownItem',
            name: 'FBA',
            icon: 'fab fa-amazon',
            action: {
                text: 'Create',
                icon: 'fas fa-plus-circle',
                to: '/fulfillments/create'
            },
            children: [
                {
                    _type: 'SidebarNavItem',
                    name: 'Fulfillments',
                    icon: 'fas fa-box-check',
                    to: '/fulfillments'
                },
                {
                    _type: 'SidebarNavItem',
                    name: 'Inbound Shipments',
                    icon: 'fas fa-truck-container',
                    to: '/fulfillments/inbound',
                },

            ]
        },
        {
            _type: 'SidebarNavDropdownItem',
            name: 'Freight',
            icon: 'fas fa-truck',
            action: {
                text: 'Create',
                icon: 'fad fa-plus-square',
                to: '/freight/new'
            },
            children: [
                {
                    _type: 'SidebarNavItem',
                    name: 'New Label',
                    icon: 'fad fa-tags',
                    to: '/freight/new'
                }
            ]
        },
        {
            _type: 'SidebarNavDropdownItem',
            name: 'Services',
            icon: 'fas fa-server',
            children: [
                {
                    _type: 'SidebarNavItem',
                    name: 'Cron Statuses',
                    icon: 'fas fa-terminal',
                    to: '/services/cronstatus'
                },
                {
                    _type: 'SidebarNavItem',
                    name: 'Shipping Labels',
                    icon: 'fas fa-tag',
                    to: '/services/logs/shipping_labels',

                },
            ]
        }
    ]
}]
