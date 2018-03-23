import tweets from './projects/tweets/tweets';
import news from './projects/news/news';
var projects = [tweets, news];
export default projects;


import firebase from 'firebase'
import 'firebase/firestore'
const firebaseApp = firebase.initializeApp({
  apiKey: 'AIzaSyB-194Oj4Z5CuHjwY8crl0iTG-zjk0ar6g',
  authDomain: 'rafah-dfc1f.firebaseapp.com',
  databaseURL: 'https://rafah-dfc1f.firebaseio.com',
  projectId: 'rafah-dfc1f',
  storageBucket: '',
  messagingSenderId: '364638682319'
})
export const db =  firebaseApp.firestore()

