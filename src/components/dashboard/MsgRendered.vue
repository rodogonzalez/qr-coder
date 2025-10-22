<script setup>


import { ref, reactive, onMounted } from 'vue'

import axios from "axios";


const props = defineProps({    
    slug: String
});


const country = reactive({
    item: []
});

onMounted(() => {
    axios.get("/country.json").then((res) => {



        function find_country(item_cursor, index, array) {
            if (item_cursor.slug == props.slug) {

                country.item = item_cursor;

            }
        }
        let items = res.data;

        items.forEach(find_country);


    });


})



</script>
<template>
    <div class="container ">
        <div class=" px-4 py-5">

            <div class="row">
                <div class="col-2"><img :src="country.item.flag" class="w-75" /></div>
                <div class="col-10">
                    <h1 class=""> {{ country.item.name }}</h1>
                </div>


            </div>


            <div class="col mb-4 mt-4" v-for="video in country.item.video_links">
                <iframe class="w-100" width="560" height="315" :src="video" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>


            </div>
            <div>
            <div v-if="country.item.description">

                <div v-if="country.item.description[0]" v-html="country.item.description[0].value"></div>
                <div v-if="country.item.description[0]" v-html="country.item.description[0].summary"></div>
                
                
            </div>
                <span  v-if="country.item.politics" >
                    <div v-if="country.item.politics[0]" v-html="country.item.politics[0].value"></div>
                </span>
                
            </div>



            <div class=" px-4 py-5">                

                <div class="row row-cols-1 row-cols-lg-5 row-cols-md-3 row-cols-sm-2 align-items-stretch g-5 py-5">


                    <div class="col" v-for="item in country.item.country_parts" v-bind:key="item.name">

                        <router-link :to="'/' + country.item.slug + '/' + item.slug">
                            <div class="   ">

                                <b class=""> {{ item.name }}</b>

                                <div class="row">
                                    <div class="picture_list_item" v-for="picture in item.pictures">

                                        <img :src="picture" class="w-100">

                                    </div>
                                </div>


                            </div>
                        </router-link >
                    </div>


                </div>

                <h2>Aerolineas </h2>
                <div class="row  align-middle ">


                    <div class="col-5 col-sm-3 col-md-3 col-lg-2 col-xl-2 col-xxl-4 p-4 card m-1 align-middle text-center" v-for="item in country.item.airlines" v-bind:key="item.name">
                        <img :src="item.logo" class="w-100" />
                        
                    </div>


                </div>



            </div>
        </div>
    </div>
</template>
