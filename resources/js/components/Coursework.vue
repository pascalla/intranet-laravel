<template>
  <div class="container-fluid">
      <h1 class="display-3 mt-3 mb-0">Upcoming Coursework</h1>
      <div class="row"  v-if="coursework.current.length > 0">
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-3" v-for="cw in coursework">
          <div class="card bg-light mt-5">
            <h5 class="card-header">{{ cw.module }} - {{ cw.title }} ({{cw.lecturer}})</h5>
            <div class="card-body">
              <h6 class="card-title">Due on {{ cw.deadline }}</h6>
              <p class="card-text">Feedback by {{ cw.feedback }}</p>

            </div>
          </div>
        </div>
      </div>
      <h1 class="display-3 mt-3 mb-0">Past Coursework</h1>
      <div class="row"  v-if="coursework.past.length > 0">
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-3" v-for="cw in coursework.past">
          <div class="card bg-light mt-5">
            <h5 class="card-header">{{ cw.module }} - {{ cw.title }} ({{cw.lecturer}})</h5>
            <div class="card-body">
              <h6 class="card-title">Due on {{ cw.deadline }}</h6>
              <p class="card-text">Feedback by {{ cw.feedback }}</p>
              <div class="text-right">
                <a href="#" class="btn btn-primary"><i class="fa fa-bell-o" aria-hidden="true"></i> Remind Me</a>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
</template>



<script>
export default {
  data(){
    return {
      year : "18/19",
    }
  },
  methods: {
    getCoursework(){
      const { year } = this;
      if(this.$store.state.courseworkdata.coursework.length < 1){
        this.$store.dispatch('courseworkdata/getCoursework', { year });
      }
    }
  },
  created: function () {
      this.getCoursework();
  },
  computed:{
    coursework: function () {
      return this.$store.state.courseworkdata.coursework
    }
  }
}
</script>
