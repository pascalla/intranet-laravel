import { authentication } from './_store/authentication.module';
import { alert } from './_store/alert.module';
import { courseworkdata } from './_store/courseworkdata.module';
import { timetabledata } from './_store/timetabledata.module';


export default {
  modules: {
    authentication,
    alert,
    courseworkdata,
    timetabledata,
  },
};
