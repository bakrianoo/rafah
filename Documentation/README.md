# RAFAH | The LOST NLP Annotation Tool

Meet Rafah. The lightweight annotation tool which designed to help NLP researchers and developers to annotate their datasets easily.

## 1. NodeJS
* Install [nodeJS](https://nodejs.org/en/) on your machine
* Ensure the it appended to your system by opening your terminal/cmd and type the next two commands. You must reach the versions numbers like what in the following image.
```
node --version
```
```
npm --version
```

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/8.jpg)



## 2. Firebase Account
* Go to [Firebase](https://firebase.google.com/) and login with your Google account.
* Open your [Firebase Console](https://console.firebase.google.com/u/0/)
* Create New Project

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/1.jpg)


* Set **Project Name** and **Region** then click on **Create Project**

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/2.jpg)


* Click **Continue**

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/3.jpg)


* From the left sidebar select **Database**. Under **Cloud Firestore** Click on **Get Started**

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/4.jpg)


* You will notified by a popup window. Select **Start in test mode** then click on **Enable** button

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/5.jpg)


* From the left sidebar select **Project Overview**. Click on **Add Firebase to your web app**

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/6.jpg)


* Copy just the configuration parameters which bordered with the orange rectangle.

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/7.jpg)


## Installation
* Download **Rafah** project into a directory
* Open "Rafah/src/firebase.js" and append the copied Firebase configuration parameters.

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/9.jpg)


* Open your terminal/cmd and change the directory to your **Rafah** project directory
```
cd rafah_path/
```
* Run this command to install the requirements
```
npm install
```

## Create a New Annotation Project
You can setup multiple annotation projects. In the next steps we are going to setup an example project for demonstration

1. We are going to setup a project to annotate some tweets.
2. You must have your data on a JSON format following a structure which each record contains tow keys. **text** for the document text, and **id** for a unique numerical ID. This is an example to demonstrate the stuff.
```
[
  {"id":1,"text":"example tweet text"},
  {"id":2,"text":"another example tweet text"}
]
```
You could reach one of these tutorials to convert your data easilly to a JSON format string.
[Python](http://developer.rhino3d.com/guides/rhinopython/python-xml-json/) | [PHP](https://www.electrictoolbox.com/php-encode-array-json/) | [R](https://cran.r-project.org/web/packages/jsonlite/vignettes/json-aaquickstart.html) |[Java](http://www.appsdeveloperblog.com/java-into-json-json-into-java-all-possible-examples/) | [c++](https://github.com/nlohmann/json)

3. Go to "rafah_path/src/projects" and create a new directory for your project "i.e **tweets**"
4. Create "rafah_path/src/projects/tweets/"**data.json** file and type your data JSON format string.
5. Create a .js file with the same project directory name. "rafah_path/src/projects/tweets/"**tweets.js**
6. Open **tweets.js** file and type the following code
```
import data from "./data.json"

var tweets = {
  'project_name': 'Tweets Project',
  'direction': 'rtl',
  'data': data,
  'text_key': 'text',
  'per_page' : 25,
  'categories': {
                'type':'single',
                'options':{'pos': 'Positive', 'neg': 'Negative'}
              },
  'highlight_categories': {
                          'loc': 'Location',
                          'org': 'Organization'
                        },
}

export default tweets;

```
7. The following image demonstrates the above code with numerical section
![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/10.jpg)

> 1 . Import the JSON data

> 2 . Configure the parameters as the following

| Parameter        | Description |
| ---------------- |:----------------------:|
| project_name     | The project name |
| direction     | **rtl** for right directional languages (Arabic, Persian, ..) or **ltr** otherwise. |
| per_page     | How many records will appear in each single page |
| categories > type     | Set *type* to **single** if you which to annotate each record to a single category or **multiple** for multi categorization.|
| categories > options     | Set which categories for annotating each data record. Set each category as "key":"label" structure  |
| highlight_categories  | This is for information extraction as you could highlight a snippet text and assign it to one or multiple of these categories. Set each category as "key":"label" structure  |

> 3 . Export the project configurations

8. Open "rafah_path/src/firebase.js" and import your new project as the following example. You can append many project by following this way
```
import tweets from './projects/tweets/tweets';

var projects = [ tweets ];
export default projects;
```

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/11.jpg)


## Run
1. Open your terminal/cmd and change to "rafah_path" directory
2. Run the project using the following command
```
npm run dev
```
3. Wait a moment then you will be redirected to the following URL
> http://localhost:8080/#/dashboard
4. Select your project from the left sidebar

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/12.jpg)


5. By running the project for the first time. It will ask you to load the data to the **Firestore** database. Please click on **Start Loading** button.

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/13.jpg)


6. It could take a while depending on your data size. Click on **Back to Project Page** to return the project page and start annotating.

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/14.jpg)



## Annotation
1. By browsing the project page. You will find your records as the following design

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/15.jpg)


2. You could assign the record category by selecting the appropriate one [or more as your configurations]

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/16.jpg)


3. You could highlight a snippet and select an appropriate category by following the instructions

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/17.jpg)


4. You could annotate the same snippet for another category

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/18.jpg)


5. Click on **save** to save the annotations for the current record or **Save All**

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/19.jpg)


## Downloading Results
1. Click on **Export Data** from the left sidebar to export the results of any project

![alt text](https://raw.githubusercontent.com/bakrianoo/rafah/master/Documentation/demo_images/20.jpg)


