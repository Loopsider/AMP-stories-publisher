export default {
  methods: {
    alertBox (options, callback) {
      if (typeof callback !== 'function') callback = null;
      let ops = Object.assign({}, options);

      ops.icon = ops.type;

      if (callback) {
        swal(ops).then(callback);
      } else {
        swal(ops);
      }

    },
    block () {
      $.blockUI({
        css: {
          border: 'none',
          padding: '5px',
          backgroundColor: '#000',
          '-webkit-border-radius': '10px',
          '-moz-border-radius': '10px',
          opacity: .5,
          color: '#fff',
        }
      });
    },
    handleClientError (error) {
      Vue.unBlock();
      console.log('Client error is : %O', error);
    },
    handleServerErrorV2 (response) {
      let errorText = '';
      if (typeof response.data === 'undefined' && typeof response.body === 'object') {
        response.data = response.body;
      }
      if (response.data.hasOwnProperty('message') && typeof response.data.message === 'string') {
        errorText += response.data.message + '\n'
      }
      /* This condition is used to support the old error format */
      let errors = response.data.payload;

      if (Array.isArray(errors) || (typeof errors === 'object' && !!errors)) {
        $.each(errors, (key, value) => {
          if (Array.isArray(value)) {
            errorText += value[0] + '\n'; //showing only the first error.
          } else {
            errorText += value + '\n'; //showing all string.
          }
        });
      }

      Vue.unBlock();
      Vue.alertBox({
        title: 'Error',
        text: errorText,
        type: 'error'
      });
    },
    handleServerError (response) {
      let errorText = '';
      if (typeof response.data === 'undefined' && typeof response.body === 'object') {
        response.data = response.body;
      }
      if (response.data.hasOwnProperty('message') && typeof response.data.message === 'string') {
        errorText += response.data.message + '\n'
      }
      /* This condition is used to support the old error format */
      let errors = response.data.hasOwnProperty('errors') ? response.data.errors :
        (response.data.hasOwnProperty('payload') ? response.data.payload : response.data);
      if (Array.isArray(errors) || typeof errors === 'object') {
        $.each(errors, (key, value) => {
          if (Array.isArray(value)) {
            errorText += value[0] + '\n'; //showing only the first error.
          } else {
            errorText += value + '\n'; //showing all string.
          }
        });
      }

      Vue.unBlock();
      Vue.alertBox({
        title: 'Error',
        text: errorText,
        type: 'error'
      });
    },
    handleServerSuccess () {
      Vue.unBlock();
      Vue.alertBox({
        title: 'Success',
        text: 'Operation done successfully',
        type: 'success'
      });
    },
    setImageUrl (type) {
      switch (type) {
        case 'success':
          return "/assets/ic-success.png";
          break;
        case 'error':
          return "/assets/ic-error.png";
          break;
        default:
          return false;
      }
    },
    showPopup (type, message) {
      this.alertBox({
        title: type.toUpperCase(),
        text: message,
        type: type
      });
      Vue.unBlock();
    },
    unBlock () {
      $.unblockUI();
    },
  }
};
