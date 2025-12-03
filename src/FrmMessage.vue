<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";
import QrScanner from 'qr-scanner'; 

const url_parser = "https://www.cryptotaller.com/qr-image?data=";

let mensaje = ref("");
let phone_number = ref("+506");
let ws_link = "";
let qr_code = "";
let qr_type = ref("text");
let videoElem  =  document.getElementById('qr_scanner_video');
let show_qr = false;


let qr_ws = 0;
let qr_text = 0;

const qrScanner = new QrScanner(
    videoElem,
    result => setResult(result)
    
    // No options provided. This will use the old api and is deprecated in the current version until next major version.
);

function init_camera(){
  qrScanner.start();
}

function setResult( result) {    
    console.log(result);
    qrScanner.stop();
    mensaje.value=result;
    //generateQR();
    setTimeout(init_camera(), 10000);
}

/*
function generateQR() {


  switch(qr_type.value){
    case 'ws':
      ws_link = url_parser + "https://wa.me/" + phone_number.value + "?text=" + encodeURI(mensaje.value);
      break
    default:
      ws_link = url_parser + encodeURI(mensaje.value);

  }


  console.log( ws_link);
    
  axios({
    method: "get",
    url: url_parser + ws_link
  }).then(function(response) {
    
    //console.log(response);
    qr_code = response.data;
    show_qr = true;
    //ws_link=
  });
}
*/
function start_camera(){  
  init_camera();
}

function stop_camera(){
  qrScanner.stop();
}


onMounted(() => { 
  mensaje.value="";
 
  show_qr=true;
});

</script>
<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <form>
          <div class="row p-4">
            
            <div class="col-12 col-lg-5"><input type="radio" value="text" id="qr_type_text" name="qr_type[]" v-model="qr_type" v-on:change="stop_camera()"/> Convertir Texto a QR</div>
            <div class="col-12 col-lg-5"><input type="radio" value="ws" id="qr_type_ws" name="qr_type[]"  v-model="qr_type" v-on:change="stop_camera()" /> Whatsapp link</div>
            <div class="col-12 col-lg-5"><input type="radio" value="read" id="qr_type_text" name="qr_type[]" v-model="qr_type" v-on:change="start_camera()"/> Leer QR</div>
          </div>

          <div class="row card p-4" v-if="qr_type === 'ws'">
            <div class="col-12 col-lg-4">Numero</div>
            <div class="col-12 col-lg-8"><input v-model="phone_number"  /></div>
          </div>

          <div class="row h-100 card p-4">
            <div class="col-12 col-lg-4">Mensaje</div>
            <div class="col-12 col-lg-8"><textarea class='w-100 h-100' v-model="mensaje" 
              
              placeholder="Ingrese el texto que desea convertir en Codigo QR"/>            
            
             
             </div>
          </div>
        </form>
      </div>
      <div class="col-12 col-lg-4">
        <div v-if="mensaje !=='' ">
          <span v-if="show_qr">
            <a  v-if="qr_type === 'ws'" :href="'https://wa.me/'+  phone_number + '?text=' +    mensaje" target="_blank">Abrir link</a> 
            <img v-if="qr_type === 'ws'"  class="qr_image" :src="url_parser + 'https://wa.me/'+  phone_number + '?text=' +    mensaje"  />
            <img v-if="qr_type === 'text'"  class="qr_image" :src="url_parser + mensaje"  />            
            <img v-if="qr_type === 'read'"  class="qr_image" :src="url_parser + mensaje"  /> 
        </span>
        </div>
      </div>
    </div>
  </div>
  
</template>
