import Login from "./components/user/Login";
import Signup from "./components/user/Signup";
import BoardComponent from "./components/board/Board";

export const routes = [
    {
        path: '/',
        name: 'home',
        component: BoardComponent,
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
        path: '*',
        component: BoardComponent,
        meta: {
            requiresAuth: true
        }
    }
]
