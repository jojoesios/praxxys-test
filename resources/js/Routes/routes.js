const routes = [{
        path: '/home',
        component: require('../components/Home.vue').default,
        name: 'Home'
    },
    // {
    //     path: '/users',
    //     component: require('../components/User.vue').default,
    //     name: 'Users'
    // },
    {
        path: '/products',
        component: require('../components/Product.vue').default,
        name: 'Products'
    },
    {
        path: '/create-product',
        component: require('../components/CreateProduct.vue').default,
        name: 'Create Products'
    },
    {
        path: '/update-product/:id',
        component: require('../components/CreateProduct.vue').default,
        name: 'Update Products',
        props: true
    },
    {
        path: '/video',
        component: require('../components/Video.vue').default,
        name: 'Video'
    },
];

export default routes;