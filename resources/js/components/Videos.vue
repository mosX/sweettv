<script lang="ts" setup>
import axios from 'axios';
import { initCustomFormatter, onMounted, ref } from 'vue'; 
    const limit = ref<Number>(10);
    const offset = ref<Number>(0);
    const data = ref<string[]>([]);

    const dropAction = (event)=>{
        files.value = event.dataTransfer.files;
    }

    const files = ref<any>('');
    const loadFileChangeHandle = (event)=>{
        var ret = document.querySelector('#loadFile[type=file]').files;

        files.value = Array.from(ret);
    }

    const activateFileLoading = (event) => {
        $('#loadFile').click();

        if(event){
            event.stopPropagation();
            event.preventDefault();
        }
    }

    const getData = (append:Boolean = false)=>{
        axios({
            url:`/videos?offset=${offset.value}&limit=${limit.value}`,
            method:'GET',                        
        }).then((ret)=>{            
            if(append){
                for(var key in ret.data){
                    data.value.push(ret.data[key]);
                }
            }else{
                data.value = ret.data;
            }
        });
    }

    const saveFile = (event)=>{
        event.preventDefault;

        const formData = new FormData();
        if(files.value[0]){
            formData.append('file',files.value[0]);
        }
        
        axios({
            url:'/videos',
            method:'POST',
            headers:{
                "Content-Type":"multipart/form-data"
            },
            data:formData
        }).then((ret)=>{
            $('#addVideoModal').modal('hide');
        });
    }

    const remove = (event,id)=>{
        event.preventDefault;
        
        axios({
            url:"/videos/"+id,
            method:'DELETE',
        }).then((ret)=>{
            offset.value = 0;
            getData();
        });
    }

    const loadMore = ()=>{
        offset.value = Number(offset.value) + 10;

        getData(true);
    }

    onMounted(()=>{
        getData();
    })
</script>

<template>
    <div class="container">
        <h2>Videos</h2>

        <div class="filter d-flex justify-content-end">
            <div data-bs-toggle="modal" data-bs-target="#addVideoModal" class="btn btn-primary">Add Video</div>            
        </div>
        <input type="file" style="display:none" @change="loadFileChangeHandle($event)" id="loadFile" accept="*/*">

        <table class="table">
            <thead>
                <tr>
                    <th>File</th>
                    <th>720</th>
                    <th>1080</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in data">
                    <td>{{ item.filename }}</td>
                    <td><a :href="`/storage/720p_${ item.filename }`" class="btn btn-primary">Preview</a></td>
                    <td><a :href="`/storage/1080p_${ item.filename }`" class="btn btn-primary">Preview</a></td>
                    <td>
                        <div @click="remove($event, item.id )" class="btn btn-primary">Remove</div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="form-group text-center">
            <div @click="loadMore()" class="btn btn-primary">LoadMore</div>
        </div>
    </div>    

    <div class="modal" id="addVideoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="dropbox" @drag.prevent @dragenter.prevent @dragover.prevent @drop.prevent="dropAction($event)" @click="activateFileLoading($event)">
                        <h4>Drop Box</h4>
                        {{ files }}
                    </div>
                    
                    <p>Modal body text goes here.</p>
                    <div class="form-group text-center">
                        <button @click="saveFile($event)" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>                
            </div>
        </div>
    </div>

</template>


<style scoped>  
    .dropbox{
        border: 1px solid #d6d6d6;
        width: 100%;
        padding:40px;
        min-height: 200px;
        text-align: center;;
    }
</style>