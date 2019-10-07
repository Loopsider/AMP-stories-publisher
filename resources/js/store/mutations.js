export const UPDATE_USER_INFO = (state, userInfo) => {
  state.userInfo.id = userInfo.id ? userInfo.id : '';
  state.userInfo.email = userInfo.email ? userInfo.email : '';
  state.userInfo.language = userInfo.language ? userInfo.language : '';
  state.userInfo.name = userInfo.name ? userInfo.name : '';
  state.userInfo.role = userInfo.role ? userInfo.role : '';
  state.userInfo.verifiedAt = userInfo.verifiedAt ? userInfo.verifiedAt : null;
};

export const LOG_IN = (state, token) => {
  state.token = (token && token.accessToken) || '';
  state.tokenExpiresIn = (token && token.expiresIn) || '';
  state.refreshTokenExpiresIn = (token && token.refreshTokenExpiresIn) || '';
};

export const LOG_OUT = (state) => {
  state.token = null;
  state.tokenExpiresIn = null;
  state.refreshTokenExpiresIn = null;
  state.userInfo = {};
};

export const UPDATE_APP_TITLE = (state, title) => {
  state.app.title = title;
};

export const UPDATE_APP_EDITABLE = (state, editable) => {
  state.app.editable = editable;
};

export const UPDATE_AMP_STORY = (state, story) => {
  state.story.id = (story && story.id) ? story.id : '';
  state.story.slug = (story && story.slug) ? story.slug : '';
  state.story.story_language = (story && story.story_language) ? story.story_language : state.userInfo.language;
  state.story.data.publisher = (story && story.data.publisher) ? story.data.publisher : '';
  state.story.data.publisherLogoSrc = (story && story.data['publisher-logo-src']) ? story.data['publisher-logo-src'] : '';
  state.story.data.posterPortraitSrc = (story && story.data['poster-portrait-src']) ? story.data['poster-portrait-src'] : '';
  state.story.data.supportsLandscape = (story && !_.isNil(story.data['supports-landscape'])) ? story.data['supports-landscape'] : true;
  state.story.data.backgroundAudio = (story && story.data['background-audio']) ? story.data['background-audio'] : '';
  state.story.data.posterSquareSrc = (story && story.data['poster-square-src']) ? story.data['poster-square-src'] : '';
  state.story.data.posterLandscapeSrc = (story && story.data['poster-landscape-src']) ? story.data['poster-landscape-src'] : '';
  state.story.data.pages = (story && story.data.pages) ? story.data.pages : [];

  // replace empty array with initial bookend data for already saved data

  state.story.data.bookend = (story && story.data.bookend && story.data.bookend.length !== 0) ? story.data.bookend : {
    bookendVersion: 'v1.0',
    shareProviders: [],
    components: []
  };

  // state.story.data.bookend =  {
  //   bookendVersion: 'v1.0',
  //   shareProviders: [],
  //   components: []
  // };

  state.story.data.css = (story && story.data.css) ? story.data.css : '';

  if (!_.isNil(story.selected)) {
    Vue.$store.dispatch('selectAMPStory', story.selected);
  }
};

export const SAVE_AMP_STORY = (state, story) => {
  const storyData = Vue.$store.state.story.data;
  const data = {
    'id': (story && story.id) ? story.id : Vue.$store.state.story.id,
    'title': Vue.$store.state.app.title,
    'slug': (story && story.slug) ? story.slug : Vue.$store.state.story.slug,
    'story_language': (story && story.data.story_language) ? story.data.story_language : Vue.$store.state.story.story_language,
    'data': {
      'background-audio': (story && story.data.backgroundAudio) ? story.data.backgroundAudio : storyData.backgroundAudio,
      'poster-portrait-src': (story && story.data.posterPortraitSrc) ? story.data.posterPortraitSrc : storyData.posterPortraitSrc,
      'poster-square-src': (story && story.data.posterSquareSrc) ? story.data.posterSquareSrc : storyData.posterSquareSrc,
      'poster-landscape-src': (story && story.data.posterLandscapeSrc) ? story.data.posterLandscapeSrc : storyData.posterLandscapeSrc,
      'publisher': (story && story.data.publisher) ? story.data.publisher : storyData.publisher,
      'publisher-logo-src': (story && story.data.publisherLogoSrc) ? story.data.publisherLogoSrc : storyData.publisherLogoSrc,
      'pages': (story && story.data.pages) ? story.data.pages : storyData.pages,
      'supports-landscape': (story && !_.isNil(story.data.supportsLandscape)) ? story.data.supportsLandscape : storyData.supportsLandscape,
      'bookend': (story && story.data.bookend) ? story.data.bookend : storyData.bookend,
      'css': (story && story.data.css) ? story.data.css : storyData.css,
    }
  };

  if (story.publish) {
    Vue.block();
    if (!_.isNil(Vue.$store.state.story.id) && !Vue.$store.state.story.new) {
      Vue.$http.put(`/story/update`, data).then((response) => {
        response.data = !_.isNil(story.selected) ? { ...response.data, selected: story.selected } : response.data;
        Vue.$store.dispatch('updateAMPStory', response.data);
        Vue.unBlock();
        Vue.alertBox({
          title: 'Success',
          text: 'Successfully saved this story!',
          type: 'success'
        });
      }, (error) => {
        console.log(error);
      }).catch(Vue.handleClientError);
    } else {
      data.storyStatus="draft";
      Vue.$http.post(`/story/create`, data).then((response) => {
        response.data = !_.isNil(story.selected) ? { ...response.data, selected: story.selected } : response.data;
        Vue.$store.dispatch('updateAMPStory', response.data);
        Vue.$store.state.story.new = false;
        Vue.unBlock();
        Vue.alertBox({
          title: 'Success',
          text: 'Successfully saved this story!',
          type: 'success'
        });
      }, (error) => {
        console.log(error);
        Vue.unBlock();
      }).catch(Vue.handleClientError);
    }
  } else {
    if (_.isNil(Vue.$store.state.story.id)) {
      data.id = Date.now();
      data.slug = data.id;
      Vue.$store.state.story.new = true;
    }
    Vue.$http.post(`/story/redis/set`, data).then((response) => {
      response.data = !_.isNil(story.selected) ? { ...response.data, selected: story.selected } : response.data;
      Vue.$store.dispatch('updateAMPStory', response.data);
    }, (error) => {
      console.log(error);
    }).catch(Vue.handleClientError);
  }
};

export const DELETE_AMP_STORY_REDIS = (state) => {
  const data = {
    id: Vue.$store.state.story.id
  };
  Vue.$http.post(`/story/redis/delete`, data).then((response) => {
    console.log('DELETE STORY FROM REDIS', response);
    Vue.$store.dispatch('clearAMPStory');
  }, (error) => {
    console.log(error);
  }).catch(Vue.handleClientError);
};

export const SELECT_AMP_STORY = (state, selected) => {
  state.story.selected.page = !_.isNil(selected.page) ? selected.page : 0;
  state.story.selected.layer = !_.isNil(selected.layer) ? selected.layer : -1;
  state.story.selected.gridArea = !_.isNil(selected.gridArea) ? selected.gridArea : -1;
  state.story.selected.element = !_.isNil(selected.element) ? selected.element : -1;
};

export const CLEAR_AMP_STORY = (state) => {
  state.story = {
    id: null,
    story_language:state.userInfo.language,
    data: {
      publisher: 'Publisher',
      publisherLogoSrc: '',
      posterPortraitSrc: '',
      supportsLandscape: true,
      backgroundAudio: '',
      posterSquareSrc: '',
      posterLandscapeSrc: '',
      pages: [],
      css: '',
      bookend: {
        bookendVersion: 'v1.0',
        shareProviders: [],
        components: []
      },
    },
    selected: {
      page: 0,
      layer: -1,
      gridArea: -1,
      element: -1,
    },
    new: false,
  }
};

export const FETCH_STORY_STYLE = (state) => {
  Vue.$http.get('/story/style').then((response) => {
    // temporarily
    if (response.data && response.data.length === 0 && _.isNil(response.data[0].slug)) {
      state.story.data.css = response.data[0].data;
    } else {
      state.story.data.css = response.data.filter(style => {
        return style.slug = 'setting'
      })[0].data;
    }
  }, (error) => {
  }).catch(Vue.handleClientError);
};

export const UPDATE_CLOUDINARY = (state, data) => {
  state.cloudinary.cloudName = data.cloudName;
  state.cloudinary.userName = data.userName;
  state.cloudinary.timestamp = data.timestamp;
  state.cloudinary.signature = data.signature;
  state.cloudinary.apiKey = data.apiKey;
};