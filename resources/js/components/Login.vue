<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-xs-8 col-sm-8 col-md-8 col-lg-4">
        <div class="card mt-5">
          <h5 class="card-header">Login</h5>
          <div class="card-body">
            <img src="/img/logo.png" class="w-50 mx-auto d-block" alt="Swansea University">
            <div v-if="alert.message" :class="`alert ${alert.type} mt-3`">{{alert.message}}</div>
            <form @submit.prevent="handleLogin">
              <div class="form-group">
                <label for="username" class="bmd-label-floating">Student Number</label>
                <input type="text" v-model="username" class="form-control" id="username" name="username" :class="{ 'is-invalid': submitted && !username }">
                <div v-show="submitted && !username" class="invalid-feedback">Username is required</div>
              </div>
              <div class="form-group">
                <label for="password" class="bmd-label-floating">Password</label>
                <input type="password" v-model="password" class="form-control" id="password" name="password" :class="{ 'is-invalid': submitted && !password }">
                <div v-show="submitted && !password" class="invalid-feedback">Password is required</div>
              </div>
              <div class="form-group">
                <button class="btn btn-outline-primary pull-right" :disabled="loggingIn">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      username: '',
      password: '',
      submitted: false,
    }
  },
  computed: {
      loggingIn () {
          return this.$store.state.authentication.status.loggingIn;
      },
      alert () {
          return this.$store.state.alert
      }
  },
  methods: {
    handleLogin(e) {
      this.submitted = true;
      const { username, password } = this;
      if(username && password){
        this.$store.dispatch('authentication/login', { username, password });
      }
    }
  }
}
</script>
