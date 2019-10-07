import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import CustomStyleCommand from './customstylecommand';
import {customStyles} from './utils';
const CUSTOMSTYLE = 'customStyle';

export default class CustomStyleEditing extends Plugin {
  constructor(editor) {
    super(editor);
    editor.config.define(CUSTOMSTYLE, {
      options: customStyles
    });
  }

  init() {
    const editor = this.editor
    const enabledOptions = editor.config.get('customStyle.options');
    const definition = _buildDefinition(enabledOptions);

    editor.conversion.attributeToElement(definition);
    editor.commands.add(CUSTOMSTYLE, new CustomStyleCommand(editor));
    editor.model.schema.extend('$text', { allowAttributes: CUSTOMSTYLE });
  }
}

// @private
function _buildDefinition(options) {
  const definition = {
    model: {
      key: 'customStyle',
      values: options.map(option => option.type).slice()
    },
    view: {}
  };
  options.forEach(option=> {
    definition.view[option.type] = {
      name: 'span',
      styles: option.style
    };
  });
  return definition;
}
