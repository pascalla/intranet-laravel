<template>
  <div class="card mt-5">
    <h5 class="card-header">Timetable <span class="pull-right text-muted small" v-if="status == 'success'">Last updated {{ lastUpdated }}</span></h5>
    <div class="card-body">
      <div>
        <!-- Normal Display -->
        <table class="table table-bordered d-none d-md-block">
          <thead>
            <tr class="text-center">
              <td style="width:5%;"></td>
              <td style="width:19%;" v-for="day in weeks">
                {{ day }}
              </td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="hour in 9">
              <td> {{ hour+8 }}</td>
              <td class="td-slot" v-for="day in 5">
                <div class="slot" v-for="(slot, index) in timetable[day-1][hour+8]">
                  <strong class="slot-name">{{ slot.module }} <span v-if="slot.module">{{ slot.lecturer }}</span></strong>
                  <div class="slot-location">{{ slot.location }}</div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Mobile Display -->
        <table class="table table-sm table-bordered d-xs-block d-sm-block d-md-none">
          <thead>
            <tr class="text-center">
              <td></td>
              <td>Monday</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>09:00</td>
              <td class="td-slot" v-for="">
                <strong class="slot-name">CS138</strong>
                <div class="slot-location">Computational Foundry CF203 (Windows Lab)</div>
              </td>
            </tr>
            <tr>
              <td>10:00</td>
              <td class="td-slot">

              </td>
            </tr>
            <tr>
              <td>11:00</td>
              <td class="td-slot">

              </td>
            </tr>
            <tr>
              <td>12:00</td>
              <td class="td-slot">
                <strong class="slot-name">CS138</strong>
                <div class="slot-location">Computational Foundry CF203 (Windows Lab)</div>
              </td>
            </tr>
            <tr>
              <td>13:00</td>
              <td class="td-slot">
                <strong class="slot-name">CS138</strong>
                <div class="slot-location">Computational Foundry CF203 (Windows Lab)</div>
              </td>
            </tr>
            <tr>
              <td>14:00</td>
              <td class="td-slot">

              </td>
            </tr>
            <tr>
              <td>15:00</td>
              <td class="td-slot">

              </td>
            </tr>
            <tr>
              <td>16:00</td>
              <td></td>
            </tr>
            <tr>
              <td>17:00</td>
              <td class="td-slot">

              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <a href="#" class="btn btn-primary">View Full Timetable</a>
    </div>
  </div>

</template>

<script>
export default {
  data(){
    return {
      week : "2019/02/04",
    }
  },
  methods: {
    getTimetable(){
      const { week } = this;
      if((moment().unix()-timestamp)>3600){
        this.$store.dispatch('timetabledata/getTimetable', { week });
      }

    }
  },
  created: function () {
      this.getTimetable();
  },
  computed:{
    timetable: function () {
      return this.$store.state.timetabledata.timetable.data.timetable
    },
    weeks: function() {
      return this.$store.state.timetabledata.timetable.data.week
    },
    status: function () {
      return this.$store.state.timetabledata.timetable.status
    },
    lastUpdated: function() {
      return moment.unix(this.$store.state.timetabledata.timetable.timestamp).fromNow();
    },
    timestamp: function() {
      return moment.unix(this.$store.state.timetabledata.timetable.timestamp).fromNow();
    }
  }
}
</script>
