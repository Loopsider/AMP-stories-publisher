export const login = ({ commit }, token) => {
  commit('LOG_IN', token);
};

export const logout = ({ commit }) => {
  commit('LOG_OUT');
};

export const updateUserInfo = ({ commit }, userInfo) => {
  commit('UPDATE_USER_INFO', userInfo);
};

export const updateAppTitle = ({ commit }, title) => {
  commit('UPDATE_APP_TITLE', title);
};

export const updateAppEditable = ({ commit }, editable) => {
  commit('UPDATE_APP_EDITABLE', editable);
};

export const updateAMPStory = ({ commit }, story) => {
  commit('UPDATE_AMP_STORY', story);
};

export const saveAMPStory = ({ commit }, story) => {
  commit('SAVE_AMP_STORY', story);
};

export const deleteAMPStoryFromRedis = ({ commit }) => {
  commit('DELETE_AMP_STORY_REDIS');
};

export const selectAMPStory = ({ commit }, selected) => {
  commit('SELECT_AMP_STORY', selected);
};

export const clearAMPStory = ({ commit }) => {
  commit('CLEAR_AMP_STORY')
};

export const updateCloudinary = ({ commit }, data) => {
  commit('UPDATE_CLOUDINARY', data)
};

export const fetchStoryStyle = ({ commit }) => {
  commit('FETCH_STORY_STYLE')
};