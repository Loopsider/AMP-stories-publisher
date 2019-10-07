import constants from './constants';

const getLayerTemplate = (slug) => {
  if (!_.isNil(slug)) {
    return constants.layerTemplates.find((template) => {
      return template.slug === slug;
    });
  }
  return null;
};

const getElement = (slug) => {
  if (!_.isNil(slug)) {
    return constants.elementTypes.find((element) => {
      return element.slug === slug;
    });
  }
  return null;
};

const getGridArea = (slug) => {
  if (!_.isNil(slug)) {
    return constants.gridAreas.find((area) => {
      return area.slug === slug;
    });
  }
  return null;
};

const isGridArea = (slug) => {
  return slug === 'thirds';
};

const getBookendShareProvider = (slug) => {
  if (!_.isNil(slug)) {
    return constants.bookend.shareProviders.find((share) => {
      return share.slug === slug;
    });
  }
  return null;
};

const getBookendComponent = (slug) => {
  if (!_.isNil(slug)) {
    return constants.bookend.components.find((comp) => {
      return comp.slug === slug;
    });
  }
  return null;
};

const toLower = text => {
  return text.toString().toLowerCase()
};

const searchTable = (items, term) => {
  if (term) {
    return items.filter(item => {
      let result = false;
      Object.keys(item).forEach((key) => {
        if (toLower(item[key]).includes(toLower(term))) {
          result = true;
          return true;
        }
      });
      return result;
    })
  }
  return items
};


const searchForStories = (items, searchObj) => {
  let stories=[];
  if (searchObj.languageField) {
    items.forEach(item =>{
      if(toLower(item.language)==toLower(searchObj.languageField)){
        stories.push(item);
      } 
    });
    items=stories
  }
  return items.filter(item => {
    let result = false;
    Object.keys(item).forEach((key) => {
      if (toLower(item[key]).includes(toLower(searchObj.searchField))) {
        result = true;
        return true;
      }
    });
    return result;
  })
  return items
};

const searchForVideos = (items, searchObj) => {
  let videos=[];
  if (searchObj.languageField) {
    items.forEach(item =>{
      if(toLower(item.language)==toLower(searchObj.languageField)){
        videos.push(item);
      } 
    });
    items=videos
  }
  return items.filter(item => {
    let result = false;
    Object.keys(item).forEach((key) => {
      if (toLower(item[key]).includes(toLower(searchObj.searchField))) {
        result = true;
        return true;
      }
    });
    return result;
  })
  return items
};


export default {
  getLayerTemplate,
  getElement,
  getGridArea,
  getBookendShareProvider,
  getBookendComponent,
  isGridArea,
  searchTable,
  searchForVideos,
  searchForStories
}