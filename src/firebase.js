import tweets from './projects/tweets/tweets';
var projects = [ tweets ];

export default projects;

import firebase from 'firebase'
import 'firebase/firestore'

const firebaseApp = firebase.initializeApp({
  apiKey: "",
    authDomain: "",
    databaseURL: "",
    projectId: "",
    storageBucket: "",
    messagingSenderId: ""
})

export const db =  firebaseApp.firestore()

