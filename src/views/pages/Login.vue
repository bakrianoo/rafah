<template>
  <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
    <CContainer>
      <CRow class="justify-content-center">
        <CCol :md="8">
          <CCardGroup>
            <CCard class="p-4">
              <CCardBody>
                <CForm>
                  <h1>Login</h1>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>
                      <CIcon icon="cil-user" />
                    </CInputGroupText>
                    <CFormInput
                      placeholder="Email"
                      autocomplete="email"
                      :value="formData.email"
                      @input="formData.email = $event.target.value"
                    />
                  </CInputGroup>
                  <CInputGroup class="mb-4">
                    <CInputGroupText>
                      <CIcon icon="cil-lock-locked" />
                    </CInputGroupText>
                    <CFormInput
                      type="password"
                      placeholder="Password"
                      autocomplete="current-password"
                      :value="formData.password"
                      @input="formData.password = $event.target.value"
                    />
                  </CInputGroup>
                  <CRow>
                    <CCol :xs="6">
                      <CButton @click="login()" color="primary" class="px-4"> Login </CButton>
                    </CCol>
                  </CRow>
                  <CRow class="my-3">

                    <h6 class="text-center">Or login with social media</h6>

                    <!-- create list of facebook, gmail and github icons -->
                    <CCol :xs="4" class="text-center">
                      <CButton color="info" class="px-4">
                        <CIcon name="cib-facebook" class="mr-1" />
                      </CButton>
                    </CCol>
                    <CCol :xs="4" class="text-center">
                      <CButton color="danger" class="px-4">
                        <CIcon name="cib-google" class="mr-1" />
                      </CButton>
                    </CCol>
                    <CCol :xs="4" class="text-center">
                      <CButton color="secondary" class="px-4">
                        <CIcon name="cib-github" class="mr-1" />
                      </CButton>
                    </CCol>
                  </CRow>
                </CForm>
              </CCardBody>
            </CCard>

          </CCardGroup>
        </CCol>
      </CRow>
    </CContainer>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data(){
    return {
      formData: {
        email: '',
        password: '',
        device_name: 'browser',
      },
      errors: {}
    }
  },
  methods: {
    login(){
      window.axios.post('/api/login', this.formData).then((response)=>{
        this.$store.commit({
          type: 'updateToken',
          value: response.data.token,
        })
        localStorage.setItem('token', response.data.token);
        
        this.$router.push('/');
      }).catch((errors)=>{
        console.log(errors);
      })
    },
    async social_login(provider){
      const newWindow = openWindow('', 'message')
      
      window.axios.post(`/api/auth/redirect/${provider}`)
      .then(response => {
          newWindow.location.href = response.data.url;
      })
      .catch(function (error) {
          console.error(error);
      });
    },
    // This method save the new token and username
    onMessage (e) {
        if (e.origin !== window.origin || !e.data.token) {
            return
        }

        localStorage.setItem('token',e.data.token)

        this.$store.commit({
          type: 'updateToken',
          value: e.data.token,
        })

        this.$router.push('/');
    },
  },
  // Waiting for the login_callback.blade.php message... (token and username).
  mounted () {
      window.addEventListener('message', this.onMessage, false)
  },
  beforeDestroy () {
    window.removeEventListener('message', this.onMessage)
  },
}

function openWindow (url, title, options = {}) {
    if (typeof url === 'object') {
        options = url
        url = ''
    }
    options = { url, title, width: 600, height: 720, ...options }
    const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screen.left
    const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screen.top
    const width = window.innerWidth || document.documentElement.clientWidth || window.screen.width
    const height = window.innerHeight || document.documentElement.clientHeight || window.screen.height
    options.left = ((width / 2) - (options.width / 2)) + dualScreenLeft
    options.top = ((height / 2) - (options.height / 2)) + dualScreenTop
    const optionsStr = Object.keys(options).reduce((acc, key) => {
        acc.push(`${key}=${options[key]}`)
    return acc
    }, []).join(',')
    const newWindow = window.open(url, title, optionsStr)
    if (window.focus) {
        newWindow.focus()
    }
    return newWindow
}
</script>
