import Login from "./components/user/Login";
import Signup from "./components/user/Signup";
import BoardsList from "./components/board/List";

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
];
