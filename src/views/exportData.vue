<template>
    <div>
        <offline @detected-condition="handleConnectivityChange"></offline>
        <b-alert class="offline" variant="danger" v-if="!online" show>Internet Connection Problem</b-alert>
        <b-card-group deck
                      class="mb-3">
            <b-card v-for="project in allProjects" bg-variant="primary"
                text-variant="white"
                v-bind:header="project.project_name"
                class="text-center">
                    <b-button bg-variant="success" @click="download(project.project_name, 'json')">Download JSON</b-button>
            </b-card>

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

            <b-modal ref="downRef" hide-footer title="Download">
              <div class="d-block text-center" id="download_placeholder">
                <h3>Your download link is ready</h3>
                <a class="btn" id="download_now">Download</a>
              </div>
              <b-btn class="mt-3" variant="outline-danger" block @click="hideModal">Close Me</b-btn>
            </b-modal>

            <b-modal ref="errRef" hide-footer title="Error">
              <div class="d-block text-center" id="download_placeholder">
                <h3>It seems that this project is empty or you need to check your internet connection.</h3>
              </div>
              <b-btn class="mt-3" variant="outline-danger" block @click="hideErrModal">Close Me</b-btn>
            </b-modal>

        </b-card-group>
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
      allProjects : [],
      loader : false,
      online : true
    }
  },
  mounted: function(){
    this.allProjects = projects
  },
  methods: {
    showModal () {
      this.$refs.downRef.show()
    },
    hideModal () {
      this.$refs.downRef.hide()
    },
    hideErrModal () {
      this.$refs.errRef.hide()
    },
    download(project, format){
      var that = this
      that.loader = true
      var query = db.collection(project)
      .orderBy('id').get()
          .then(snapshot => {
              var records = []
              snapshot.forEach(doc => {
                  records.push({'id': doc.id, 'data': doc.data()})
              });

              setTimeout(function(){
                that.loader = false
                if(records.length == 0){
                  that.$refs.errRef.show()
                } else {
                  that.getFile(project, records, format)
                }

              }, 2000);
          })
          .catch(err => {
              console.log('Error getting documents', err);
          });
    },
    getFile(project, data, format){
        var output = JSON.stringify(data);

        var link = document.getElementById('download_now')
        link.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(output));
        link.setAttribute('download', project+".csv");
        document.getElementById('download_placeholder').appendChild(link)
        document.querySelector('#download_now').click()

        this.$refs.downRef.show()
    },
     handleConnectivityChange(status) {
       console.log(status);
      this.online = status
    }
  }
}
</script>
