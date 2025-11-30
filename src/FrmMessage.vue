<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";
import QrScanner from 'qr-scanner'; 

const url_parser = "/qr-image?data=";

let mensaje = ref("");
let phone_number = ref("+506");
let ws_link = "";
let qr_code = "";
let qr_type = ref("text");
let videoElem  =  document.getElementById('qr_scanner_video');

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
    generateQR();
    setTimeout(init_camera(), 10000);
}


function generateQR() {


  switch(qr_type.value){
    case 'ws':
      ws_link = "https://wa.me/" + phone_number.value + "?text=" + encodeURI(mensaje.value);
      break
    default:
      ws_link = encodeURI(mensaje.value);

  }
    
  axios({
    method: "get",
    url: url_parser + ws_link
  }).then(function(response) {
    
    //console.log(response);
    qr_code = response.data;
    //ws_link=
  });
}

onMounted(() => {
  init_camera();
});

</script>
<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <form>
          <div class="row p-4">
            <div class="col-12 col-lg-5"><input type="radio" value="text" id="qr_type_text" name="qr_type[]" v-model="qr_type" /> Convertir Texto a QR</div>
            <div class="col-12 col-lg-5"><input type="radio" value="ws" id="qr_type_ws" name="qr_type[]"  v-model="qr_type"/> Whatsapp link</div>
            
          </div>

          <div class="row card p-4" v-if="qr_type === 'ws'">
            <div class="col-12 col-lg-4">Numero</div>
            <div class="col-12 col-lg-8"><input v-model="phone_number" v-on:keyup="generateQR" /></div>
          </div>

          <div class="row h-100 card p-4">
            <div class="col-12 col-lg-4">Mensaje</div>
            <div class="col-12 col-lg-8"><textarea class='w-100 h-100' v-model="mensaje" v-on:keyup="generateQR" /></div>
          </div>
        </form>
      </div>
      <div class="col-12 col-lg-4">
        <div v-if="mensaje !=='' ">
        <a  v-if="qr_type === 'ws'" :href="ws_link" target="_blank">Abrir link</a> <img id="qr_generated" class="qr_image" :src="qr_code" />
        </div>
      </div>
    </div>
  </div>
  
</template>
