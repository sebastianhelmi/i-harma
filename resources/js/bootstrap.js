import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

import * as Popper from "@popperjs/core";
window.Popper = Popper;
import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;
