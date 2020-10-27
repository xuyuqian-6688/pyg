// 引入axios
import Axios from 'axios';
// 自定义插件对象
var myaxios = {};
myaxios.install = function (vue) {
  // 设置axios请求的URL，此后axios发送的请求全部执行本地址
  var axios_obj = Axios.create({
    baseURL: 'http://PygApi:8020/api/'
  })

  // 使用axios拦截器，在请求签进行判断
  axios_obj.interceptors.request.use(function (conf) {
    conf.headers.Authorization = localStorage.getItem('token');
    if (conf.url == 'login') {
      delete conf.headers.Authorization
    }else if(conf.url == 'captcha'){
      delete conf.headers.Authorization
    }
    // console.log(conf);
    // 将拦截器的操作返回给axios 对象
    return conf;
  })


  // 将设置好的axios对象赋值给Vue实例的原型
  // 之后可以在Vue中直接只用 this.$myHttp 使用axios发送请求
  vue.prototype.$http = axios_obj;
}
// 将插件以 模块 方式导出
export default myaxios;