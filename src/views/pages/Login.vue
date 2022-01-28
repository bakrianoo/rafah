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
                  <p class="text-medium-emphasis">Sign In to your account</p>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>
                      <CIcon icon="cil-user" />
                    </CInputGroupText>
                    <CFormInput
                      placeholder="Username"
                      autocomplete="username"
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
                      <CButton @click="social_login('github')" color="primary" class="px-4"> Github </CButton>
                    </CCol>
                    <CCol :xs="6" class="text-right">
                      <CButton color="link" class="px-0">
                        Forgot password?
                      </CButton>
                    </CCol>
                  </CRow>
                </CForm>
              </CCardBody>
            </CCard>
            <CCard class="text-white bg-primary py-5" style="width: 44%">
              <CCardBody class="text-center">
                <div>
                  <h2>Sign up</h2>
                  <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                    sed do eiusmod tempor incididunt ut labore et dolore magna
                    aliqua.
                  </p>
                  <CButton color="light" variant="outline" class="mt-3">
                    Register Now!
                  </CButton>
                </div>
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
