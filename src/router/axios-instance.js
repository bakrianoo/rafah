import axios from 'axios'
import config from '@/env.config.js'

const axios_instance = axios.create({
  withCredentials: true,
  baseURL: config.API_BASE_URL
});

export {axios_instance};