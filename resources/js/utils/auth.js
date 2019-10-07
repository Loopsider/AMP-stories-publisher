import store from '../store';
import axios from '../axios';

/**
 * Check if token is valid and is not expired
 * @param token
 * @returns {boolean}
 */
export const checkValidToken = (tokenExpiresIn) => {
  if (tokenExpiresIn) {
    if (new Date(tokenExpiresIn) > Date.now()) {
      return true;
    }
  }
  return false;
};

/**
 * Allow next action only if user is authenticated and token is not expired
 * @param to
 * @param from
 * @param next
 */
export const ifAuthenticated = (to, from, next) => {
  if (store.state.token != null && checkValidToken(store.state.tokenExpiresIn)) {
    if (store.state.userInfo.verifiedAt == null) {
      next('/auth/verify-email');
      return;
    }
    next();
    return;
  }
  next('/auth/login');
};

/**
 * Allow next action only if user is not authenticated or token is expired
 * @param to
 * @param from
 * @param next
 */
export const ifNotAuthenticated = (to, from, next) => {
  if (store.state.token === null || !checkValidToken(store.state.tokenExpiresIn)) {
    next();
    return;
  }
  if (store.state.userInfo.verifiedAt == null) {
    next('/auth/verify-email');
    return;
  }
  next('/backoffice');
};


/**
 * Check if user is authenticated and token is not expired
 * @returns {boolean}
 */
export const isAuthenticated = () => {
  return store.state.token != null && checkValidToken(store.state.tokenExpiresIn);
};

/**
 * Actions after login
 * @param userInfo
 * @param token
 */
export const handleLogin = (userInfo, token) => {
  store.dispatch('login', token);
  store.dispatch('updateUserInfo', userInfo);
  axios.defaults.headers['Authorization'] = 'Bearer ' + token.accessToken;
};

/**
 * Actions after log out
 */
export const handleLogout = () => {
  store.dispatch('logout');
  delete axios.defaults.headers['Authorization'];
};

