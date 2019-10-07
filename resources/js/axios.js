import axios from 'axios';
import store from './store';

const API_URL = '/api';

let bearHeader = '';
const token = store.state.token;
if (token) {
  bearHeader = 'Bearer ' + token;
}

export default axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Authorization': bearHeader
  }
});
