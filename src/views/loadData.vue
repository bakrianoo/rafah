<template>
  <div class="animated fadeIn">
    <offline @detected-condition="handleConnectivityChange"></offline>
    <b-alert class="offline" variant="danger" v-if="!online" show>Internet Connection Problem</b-alert>

    <b-card v-if="loaded" class="text-left">
      <b-button @click="$router.push({ name: 'Project', params: { id: project['project_name'] }})"
       class="back-btn" size="lg" variant="success">
                Back to Project Page
      </b-button>
    </b-card>

    <b-card v-else class="text-left">
      <b-progress v-if="counter > 1" :value="counter" :max="max" show-progress animated></b-progress>
      <b-button @click="$router.push({ name: 'Project', params: { id: project['project_name'] }})"
       v-if="counter >= max" class="back-btn" size="lg" variant="success">
                Back to Project Page
      </b-button>
    </b-card>

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
      my_name : "",
      allProjectsRef : false,
      project : false,
      projectRef : false,
      counter : 0,
      max : 100,
      loaded : false,
      online : true
    }
  },
  mounted: function(){
    var _name = this.$route.params.id;
    this.project = projects.find(function (proj) { return proj.project_name === _name; });



    this.getProject();
  },
   beforeRouteUpdate(to, from, next) {
    var _name = to.params.id;
    this.project = projects.find(function (proj) { return proj.project_name === _name; });
    this.getProject();
    next()
  },
  methods: {
    getProject(){
      var that = this;
      // var project = this.project;
      that.allProjectsRef =  db.collection(that.project['project_name']);
        that.allProjectsRef.get().then(function(querySnapshot) {
              that.max = that.project.data.length;
              var def_category = ""
              if (that.project['categories'] != undefined &&
                  that.project['categories']['type'] != undefined &&
                  that.project['categories']['type'] == 'multiple') {
                  def_category = []
              }

              for(var ind in that.project.data){

                var row = that.project.data[ind];
                if( row != undefined && row[ that.project['text_key'] ] != undefined ){

                  var record = {
                    'text': row[ that.project['text_key'] ],
                    'category' : def_category,
                    'highlight_tags' : []
                  }
                  for(var prob in row){
                    if (prob == that.project['text_key']) { continue; }
                    record[prob] = row[prob]
                  }

                  if(row['id'] !== undefined)
                    var key = parseInt(row['id'])
                  else
                    var key = parseInt(ind) + 1

                  that.saveRecord(key, record)
            }
          }
        });

    },
    handleConnectivityChange(status) {
      this.online = status
    },
    saveRecord(key, record){
      var that = this
      var query = that.allProjectsRef.doc(key.toString()).get().then(function(querySnapshot) {
            if(querySnapshot.size > 0) { // if existed
              console.log(key, record);
              that.allProjectsRef.doc(key.toString()).update(record)
            } else { // new record
              console.log(key, record);
              that.allProjectsRef.doc(key.toString()).set(record)
            }
            that.counter += 1
      });

    }


  }
}
</script>
