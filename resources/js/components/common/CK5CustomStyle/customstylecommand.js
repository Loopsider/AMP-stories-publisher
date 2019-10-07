import Command from '@ckeditor/ckeditor5-core/src/command';

const CUSTOMSTYLE = 'customStyle';

export default class CustomStyleCommand extends Command {

  refresh () {
    const model = this.editor.model;
    const doc = model.document;
    this.value = doc.selection.getAttribute(CUSTOMSTYLE);
    this.isEnabled = model.schema.checkAttributeInSelection(doc.selection, CUSTOMSTYLE);
  }

  execute (options = {}) {
    const model = this.editor.model;
    const document = model.document;
    const selection = document.selection;
    const customStyle = options.value;

    model.change(writer => {
      const ranges = model.schema.getValidRanges(selection.getRanges(), CUSTOMSTYLE);

      if (selection.isCollapsed) {
        const position = selection.getFirstPosition();

        if (selection.hasAttribute(CUSTOMSTYLE)) {
          const isSameCustomStyle = value => {
            return value.item.hasAttribute(CUSTOMSTYLE) && value.item.getAttribute(CUSTOMSTYLE) === this.value;
          };

          const customStyleStart = position.getLastMatchingPosition(isSameCustomStyle, { direction: 'backward' });
          const customStyleEnd = position.getLastMatchingPosition(isSameCustomStyle);
          const customStyleRange = writer.createRange(customStyleStart, customStyleEnd);

          if (!customStyle || this.value === customStyle) {
            writer.removeAttribute(CUSTOMSTYLE, customStyleRange);
            writer.removeSelectionAttribute(CUSTOMSTYLE);
          } else {
            writer.setAttribute(CUSTOMSTYLE, customStyle, customStyleRange);
            writer.setSelectionAttribute(CUSTOMSTYLE, customStyle);
          }
        } else if (customStyle) {
          writer.setSelectionAttribute(CUSTOMSTYLE, customStyle);
        }
      } else {
        for (const range of ranges) {
          if (customStyle) {
            writer.setAttribute(CUSTOMSTYLE, customStyle, range);
          } else {
            writer.removeAttribute(CUSTOMSTYLE, range);
          }
        }
      }
    });
  }
}
