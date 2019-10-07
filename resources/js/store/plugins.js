import { STORAGE_KEY } from './state'

const localStoragePlugin = store => {
  store.subscribe((mutation, state) => {
    const syncedData = {
      token: state.token,
      tokenExpiresIn: state.tokenExpiresIn,
      refreshTokenExpiresIn: state.refreshTokenExpiresIn,
      refreshTokenAlreadyExpired: state.refreshTokenAlreadyExpired,
      userInfo: state.userInfo,
    };

    localStorage.setItem(STORAGE_KEY, JSON.stringify(syncedData));

    if (mutation.type === 'CLEAR_ALL_DATA') {
      localStorage.removeItem(STORAGE_KEY);
    }
  })
};

export default [localStoragePlugin];
