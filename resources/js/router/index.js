import Vue from 'vue'
import Router from 'vue-router'
import { ifAuthenticated, ifNotAuthenticated } from '../utils/auth';

// Containers
import MainContainer from '../containers/MainContainer';
import AuthContainer from '../containers/AuthContainer';

// Views
import Videos from '../views/Videos'
import Stories from '../views/Stories'
import Video from '../views/Video'
import Story from '../views/Story'
import Admin from '../views/Admin'
import Analytics from '../views/Analytics'

// Auth
import Login from '../views/Auth/Login';
import SignUp from '../views/Auth/SignUp';
import ForgotPassword from '../views/Auth/ForgotPassword';
import ResetPassword from '../views/Auth/ResetPassword';
import VerifyEmail from '../views/Auth/VerifyEmail';

import Page404 from '../views/Errors/page404';

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/backoffice',
      redirect: '/backoffice/stories',
      name: 'BackOffice',
      component: MainContainer,
      children: [
        {
          path: 'videos',
          name: 'Videos',
          component: Videos
        },
        {
          path: 'stories',
          name: 'Stories',
          component: Stories
        },
        {
          path: 'video/:id',
          name: 'Video',
          component: Video
        },
        {
          path: 'video',
          name: 'Video',
          component: Video
        },
        {
          path: 'story/:id',
          name: 'Story',
          component: Story
        },
        {
          path: 'story',
          name: 'Story',
          component: Story
        },
        {
          path: 'analytics',
          name: 'Analytics',
          component: Analytics
        },
        {
          path: 'admin',
          name: 'Admin',
          component: Admin
        },
      ],
      beforeEnter: ifAuthenticated
    },
    {
      path: '/auth',
      name: 'Auth',
      component: AuthContainer,
      children: [
        {
          path: 'login',
          name: 'Login',
          component: Login,
          beforeEnter: ifNotAuthenticated
        },
        {
          path: 'register',
          name: 'SignUp',
          component: SignUp,
          beforeEnter: ifNotAuthenticated
        },
        {
          path: 'forgot-password',
          name: 'ForgotPassword',
          component: ForgotPassword,
          beforeEnter: ifNotAuthenticated
        },
        {
          path: 'reset-password/:token',
          name: 'ResetPassword',
          component: ResetPassword,
          beforeEnter: ifNotAuthenticated
        },
        {
          path: 'verify-email',
          name: 'EmailConfirmation',
          component: VerifyEmail,
          // beforeEnter: ifAuthenticated
        },
      ]
    },
    {
      path: '*',
      name: 'PageNotFound',
      component: Page404,
    }
  ]
})
