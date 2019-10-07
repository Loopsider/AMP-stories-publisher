import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import ButtonView from '@ckeditor/ckeditor5-ui/src/button/buttonview';
import { createDropdown, addToolbarToDropdown } from '@ckeditor/ckeditor5-ui/src/dropdown/utils';

const CUSTOMSTYLE = 'customStyle';

export default class CustomStyleUI extends Plugin {

  /**
   * @inheritDoc
   */
  init () {
    const editor = this.editor;
    const componentFactory = editor.ui.componentFactory;
    const options = editor.config.get('customStyle.options');

    options.forEach(option => this._addButton(option));

    componentFactory.add('customStyle', locale => {
      const dropdownView = createDropdown(locale);

      const buttons = options.map(option => componentFactory.create(`customStyle:${ option.type }`));
      addToolbarToDropdown(dropdownView, buttons);

      // Configure dropdown properties an behavior.
      dropdownView.buttonView.set({
        label: 'Custom Style',
        withText: true,
        tooltip: false
      });
      dropdownView.toolbarView.isVertical = true;
      // Enable button if any of the buttons is enabled.
      dropdownView.bind('isEnabled').toMany(buttons, 'isEnabled', (...areEnabled) => areEnabled.some(isEnabled => isEnabled));

      return dropdownView;
    });
  }


  _addButton (option) {
    const editor = this.editor;

    editor.ui.componentFactory.add(`customStyle:${ option.type }`, locale => {
      const command = editor.commands.get(CUSTOMSTYLE);
      const buttonView = new ButtonView(locale);

      buttonView.set({
        label: option.label,
        withText: true,
        tooltip: false
      });

      // Bind button model to command.
      buttonView.bind('isEnabled').to(command);
      buttonView.bind('isOn').to(command, 'value', value => value === option.type);

      // Execute command.
      this.listenTo(buttonView, 'execute', () => {
        editor.execute(CUSTOMSTYLE, { value: option.type });
        editor.editing.view.focus();
      });

      return buttonView;
    });
  }
}