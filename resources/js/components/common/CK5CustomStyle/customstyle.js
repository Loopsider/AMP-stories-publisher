import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import CustomStyleEditing from './customstyleediting';
import CustomStyleUI from './customstyleui';

import ObservableMixin from '@ckeditor/ckeditor5-utils/src/observablemixin';
import mix from '@ckeditor/ckeditor5-utils/src/mix';

export default class CustomStyle extends Plugin {
  /**
   * @inheritDoc
   */
  static get requires () {
    return [CustomStyleEditing, CustomStyleUI];
  }

  /**
   * @inheritDoc
   */
  static get pluginName () {
    return 'customStyle';
  }
}
mix( CustomStyle, ObservableMixin );
