<template>

  <div v-if="isNew">
    <offline @detected-condition="handleConnectivityChange"></offline>
    <b-alert class="offline" variant="danger" v-if="!online" show>Internet Connection Problem</b-alert>
    <div v-if="loader" class="loader">
      <div class="atom-spinner">
        <div class="spinner-inner">
          <div class="spinner-line"></div>
          <div class="spinner-line"></div>
          <div class="spinner-line"></div>
          <!--Chrome renders little circles malformed :(-->
          <div class="spinner-circle">
            &#9679;
          </div>
        </div>
      </div>
    </div>

    <b-card>
      <p> You need to load your project data before starting. </p>
      <b-button  @click="loadProject()" size="lg" variant="success">
                Start Loading
      </b-button>
    </b-card>
  </div>
  <div v-else class="animated fadeIn">
    <b-alert class="offline" variant="danger" v-if="noResults" show>No Retrieved Results</b-alert>
    <offline @detected-condition="handleConnectivityChange"></offline>
    <b-alert class="offline" variant="danger" v-if="!online" show>Internet Connection Problem</b-alert>
    <div v-if="loader" class="loader">
      <div class="atom-spinner">
        <div class="spinner-inner">
          <div class="spinner-line"></div>
          <div class="spinner-line"></div>
          <div class="spinner-line"></div>
          <!--Chrome renders little circles malformed :(-->
          <div class="spinner-circle">
            &#9679;
          </div>
        </div>
      </div>
    </div>

    <b-card v-for="rec in records" v-bind:class="{ 'text-left': project.direction == 'lrt',  'text-right': project.direction == 'rtl'  }">
      <b-form-group id="exampleInputGroup1"

                    label-for=""
                    description="">
        <b-form-input
                      type="text"
                      v-model="rec.data.text"
                      v-bind:class="{'rtl': project.direction == 'rtl'}"
                      placeholder="Enter email">
        </b-form-input>
      </b-form-group>

      <b-form-group label="Categories" v-if="project.categories.type == 'single'" v-bind:value="rec.data.category" v-model="rec.data.category">
        <div v-bind:class="{ 'elem-left': project.direction == 'lrt',  'elem-right': project.direction == 'rtl'  }" v-for="(lbl,opt) in project.categories.options" >
            <label >{{lbl}}</label>
            <input @change="getSelectionText(rec)" type="radio" :value="opt" v-model="rec.data.category">
        </div>
      </b-form-group>

      <b-form-group label="Categories" v-else v-bind:value="rec.data.category" v-model="rec.data.category">
        <div v-bind:class="{ 'elem-left': project.direction == 'lrt',  'elem-right': project.direction == 'rtl'  }"  v-for="(lbl,opt) in project.categories.options" >
            <label >{{lbl}}</label>
            <input @change="getSelectionText(rec)" type="checkbox" :value="opt" v-model="rec.data.category">
        </div>
      </b-form-group>

      <b-form-group label="Highlighting" v-model="rec.data.highlight_category">
        <div v-bind:class="{ 'elem-left': project.direction == 'lrt',  'elem-right': project.direction == 'rtl'  }"  v-for="(lbl,opt) in project.highlight_categories" >

            <b-button @click="getSelectionText(rec, opt)" size="sm" variant="info">
                      {{lbl}}
            </b-button>

        </div>
      </b-form-group>

      <b-form-group label="Tags">
        <div v-bind:class="{ 'elem-left': project.direction == 'lrt',  'elem-right': project.direction == 'rtl'  }" v-for="tag in rec.data.highlight_tags" >
            <b-button  variant="primary">
              {{tag.text}}
              <b-badge class="badge-option" v-for="opt in tag.tags" variant="light">
                {{opt}}
              </b-badge>
              <b-badge @click="removeTag(rec, tag)" class="badge-remove" variant="light">
                X
              </b-badge>
            </b-button>
        </div>
      </b-form-group>


      <b-button @click="saveRecord(rec)" size="sm" variant="success">
                Save
      </b-button>


    </b-card>
    <b-button  @click="saveAll()" class="save-all" size="lg" variant="success">
                Save All
      </b-button>

      <b-pagination-nav :base-url="'#/project/'+project.project_name+'/'"
        align="center" :number-of-pages="noPages" v-model="page" />

    <br>
  </div>
</template>

<script>
import { db } from '../firebase';
import projects  from '../firebase';
import offline from 'v-offline';


export default {
  name: 'app',
  components: {
    offline
  },
  data () {
    return {
      _name : "",
      allProjectsRef : false,
      isNew : false,
      project : false,
      projectRef : false,
      page : 1,
      perPage : 10,
      start : 0,
      offset : 0,
      total : 0,
      noPages : 1,
      records : [],
      online : true,
      loader: true,
      noResults : false
    }
  },
  mounted: function(){
    this.loader = true
    this.noResults = false

    var _name = this.$route.params.id;
    this.allProjectsRef =  db.collection(_name);
    this.project = projects.find(function (proj) { return proj.project_name === _name; });
    if(this.$route.params.page !== undefined && parseInt(this.$route.params.page) > 0){
        this.page = parseInt(this.$route.params.page);
    }
    this.getProject();
  },
  created: function(){},
   beforeRouteUpdate(to, from, next) {
    this.loader = true
    this.noResults = false

    var _name = to.params.id;
    this.allProjectsRef =  db.collection(_name);
    this.project = projects.find(function (proj) { return proj.project_name === _name; });
    if(to.params.page !== undefined && parseInt(to.params.page) > 0){
        this.page = parseInt(to.params.page);
    }

    this.getProject();
    next()
  },
  methods: {
    loadProject(){
      this.$router.push({ name: 'loadData', params: { id: this.project['project_name'] }})
    },
    getProject(){
      var that = this;
      // pagination
      if(that.project['per_page'] !== undefined && parseInt(that.project['per_page']) > 0){
        that.perPage = parseInt(that.project['per_page']);
      }

      that.total = that.project.data.length
      that.start = ((that.page - 1) * that.perPage) + 1
      that.offset = ( that.perPage + that.start ) - 1
      that.noPages = Math.ceil( that.total / that.perPage )

      that.records = [];
      var query = that.allProjectsRef
      .orderBy('id')
      .startAt(that.start).endAt(that.offset)
      .get()
          .then(snapshot => {
              if(!that.online){
                that.noResults = true
                this.isNew = false
              } else if(snapshot.size == 0){
                if(that.page > 1) {
                    that.noResults = true
                  } else {
                    this.isNew = true;
                  }
              }
              snapshot.forEach(doc => {
                  that.records.push({'id': doc.id, 'data': doc.data()})
              });
              that.loader = false
          })
          .catch(err => {
              console.log('Error getting documents', err);
          });

    },
    saveRecord(rec){
      this.allProjectsRef.doc(rec.id.toString()).update(rec.data);
    },
    saveAll(){
      for(var ind in this.records){
        this.allProjectsRef.doc(this.records[ind].id.toString()).update(this.records[ind].data);
      }
    },
   getSelectionText(rec, option) {
        if(option == null || option == undefined || option.trim() == "")
          return false

        var text = "";
        var activeEl = document.activeElement;
        var activeElTagName = activeEl ? activeEl.tagName.toLowerCase() : null;
        if (
          (activeElTagName == "textarea") || (activeElTagName == "input" &&
          /^(?:text|search|password|tel|url)$/i.test(activeEl.type)) &&
          (typeof activeEl.selectionStart == "number")
        ) {
            text = activeEl.value.slice(activeEl.selectionStart, activeEl.selectionEnd);
        } else if (window.getSelection) {
            text = window.getSelection().toString();
        }
        if(text.trim() != ""){
          // search for existed tag
          var found = false
          for(var ind in rec.data.highlight_tags){
            var _tag = rec.data.highlight_tags[ind]
            if(_tag['text'] != undefined && _tag['text'] == text.trim()) {
              rec.data.highlight_tags[ind]['tags'].push(option)
              found = true
              break
            }
          }
          // set new if not existed before
          setTimeout(function(){
            if(!found){
                rec.data.highlight_tags.push({'text': text.trim(), 'tags': [option]});
            }
          },200);


        }
        return text;
    },
    removeTag(rec, tag){
      var allTags = rec.data.highlight_tags
      rec.data.highlight_tags = []

      for(var ind in allTags){
        if(allTags[ind]['text'] !== tag['text']){
          rec.data.highlight_tags.push( allTags[ind] )
        }
      }
    },
    pageChange(x){
      console.log(x);
    },
    handleConnectivityChange(status) {
      this.online = status
    }
  }
}
</script>
