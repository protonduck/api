import Login from "./components/user/Login";
import Signup from "./components/user/Signup";
import BoardsList from "./components/board/List";
import ProductsList from "./components/product/App";

export const routes = [
    {
        path: '/',
        name: 'home',
        component: BoardsList,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/products',
        name: 'products',
        component: ProductsList,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/signup',
        name: 'signup',
        component: Signup
    },
    {
        path: '/catergories',
        name: 'catergoryAdd',
    },
    {
        path: '*',
        component: BoardsList,
        meta: {
            requiresAuth: true
        }
    }
]
