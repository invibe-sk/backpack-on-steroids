<template>
  <div class="gallery">
    <label>{{ label ? label : name }}</label>

    <!-- Preview -->
    <div class="row" v-if="files && files.length > 0 && !selectedSingleFile && preview">
      <div class="col-2 mb-3" v-for="(file, index) in files">
        <div class="square">
          <div class="content">
            <a href="#" @click.prevent="deleteTempFile(index)" class="delete-link text-danger"><i
                class="la la-trash"></i></a>
            <img :src="file.url" loading="lazy" class="preview-image">
          </div>
        </div>
      </div>
    </div>

    <div class="row" v-if="files && files.length > 0 && !preview">
      <div class="col-12 col-md-6">
        <div class="file-item position-relative" v-for="(file, index) in files">
          <span><i class="la la-file"></i> {{ file.name }}</span>
          <a href="#" @click.prevent="deleteTempFile(index)" class="delete-link text-danger"><i class="la la-trash"></i></a>
        </div>
      </div>
    </div>

    <VueCropper class="mb-3 w-50" v-show="selectedSingleFile && preview" ref="cropper" :src="selectedSingleFile"
                :aspect-ratio="aspectRatio" :zoomable="false" alt="Source Image"></VueCropper>

    <div class="gallery-upload-wrapper d-flex" v-if="selectedSingleFile">
      <a href="#" @click.prevent="saveCroppedImage" class="btn btn-primary delete-all-link mr-3">Potvrdiť</a>
      <a href="#" @click.prevent="discardCroppedImage" class="btn btn-danger delete-all-link">Zrušiť</a>
    </div>

    <div class="error-message text-danger mb-3" v-if="notUploadedFiles.length > 0">
      {{ notUploadedFiles.length > 1 ? "Súbory" : "Súbor" }} {{ notUploadedFiles.join(", ") }} sa nepodarilo nahrať,
      pretože {{ notUploadedFiles.length > 1 ? "sú väčšie" : "je väčší" }} ako {{ this.filesize }} MB
    </div>

    <!-- Uploader -->
    <div class="gallery-upload-wrapper d-flex" v-if="!selectedSingleFile">
      <label class="gallery-upload btn btn-primary mb-0 mr-3" :class="{'disabled': loading}">
        <input :accept="accept" type="file" ref="fileUploader" :disabled="loading" :multiple="multiple"
               @change="onFileChange">
        <span v-if="!loading">Nahrať {{
            files && files.length && multiple > 0 ? 'ďalšie' : ''
          }} {{ multiple ? plural : singular }}</span>
        <span v-else>Nahrávam ...</span>
      </label>
      <a href="#" v-if="files && files.length > 0" @click.prevent="deleteAllFiles"
         class="btn btn-danger delete-all-link"><i class="la la-trash"></i>
        {{ multiple && files.length > 1 ? "Vymazať všetky " + plural : "Vymazať " + singular }}</a>
    </div>

    <input type="hidden" :name="name" :value="JSON.stringify(fieldValue)">
  </div>
</template>

<script>
import VueCropper from 'vue-cropperjs'
import 'cropperjs/dist/cropper.css'

export default {
  name: "Files",
  components: {
    VueCropper
  },
  props: {
    label: {
      type: String,
    },
    name: {
      type: String,
      required: true,
    },
    multiple: {
      type: Boolean,
      default: false,
    },
    preview: {
      type: Boolean,
      default: false,
    },
    translatable: {
      type: Boolean,
      default: false,
    },
    lang: {
      type: String,
    },
    crop: {
      type: Boolean,
      default: false,
    },
    aspectRatio: {
      type: Number,
    },
    singular: {
      type: String,
      default: 'súbor'
    },
    plural: {
      type: String,
      default: 'súbory'
    },
    accept: {
      type: String,
    },
    oldValue: {
      type: Object,
    },
    className: {
      type: String,
    },
    classId: {
      type: Number,
    },
    filesize: {
      type: Number,
      default: 10,
    },
  },
  computed: {
    fieldValue: function () {
      return {
        files: this.files,
        temp: this.files.filter((file) => file.type === "temp").map((file) => file.id),
        removedFiles: this.removedFiles,
        multiple: this.multiple,
        translatable: this.translatable,
        lang: this.lang,
      }
    },
    showCropper: function () {
      return !this.multiple && this.crop && this.preview
    },
  },
  data() {
    return {
      selectedSingleFile: null,
      selectedSingleFileName: null,
      selectedSingleFileMimeType: null,
      fileModel: null,
      url: null,
      files: [],
      removedFiles: {
        temp: [],
        media: [],
      },
      notUploadedFiles: [],
      promises: [],
      loading: false,
    }
  },
  methods: {
    initCropper(file) {
      this.selectedSingleFileMimeType = file.type;
      this.selectedSingleFileName = file.name;

      if (typeof FileReader === 'function') {

        const reader = new FileReader()

        reader.onload = (event) => {
          this.selectedSingleFile = event.target.result
          this.$refs.cropper.replace(this.selectedSingleFile)
        }

        reader.readAsDataURL(file)
      } else {
        alert('Sorry, FileReader API not supported')
      }
    },
    discardCroppedImage() {
      this.selectedSingleFile = null;
      this.selectedSingleFileName = null;
      this.selectedSingleFileMimeType = null;
    },
    saveCroppedImage() {
      this.cropedImage = this.$refs.cropper.getCroppedCanvas().toDataURL()

      this.$refs.cropper.getCroppedCanvas().toBlob((blob) => {
        this.promises.push(this.uploadFilePromise(this.getFormData(blob, this.selectedSingleFileName)));
        this.uploadAllImages();
      }, this.selectedSingleFileMimeType)

    },
    onFileChange(e) {

      e.target.files.forEach((file) => {
        let sizeInMb = (file.size / 1000 / 1000);

        if (sizeInMb > this.filesize) {
          this.notUploadedFiles.push(file.name);
        } else if (this.showCropper) {
          this.initCropper(file)
        } else {
          this.promises.push(this.uploadFilePromise(this.getFormData(file)));
        }
      });

      if (!this.showCropper) {
        this.uploadAllImages();
      }

      this.setFileInputToNull();

    },
    uploadAllImages() {

      this.loading = true;

      Promise.all(this.promises).then((responses) => {
        responses.forEach((response) => {
          if (this.files === null) {
            this.files = [response.data]
          } else if (!this.multiple && this.files.length > 0) {
            this.removedFiles[this.files[0].type].push(this.files[0].id);
            this.files = [response.data];
          } else {
            this.files.push(response.data);
          }
        })
      }).catch((e) => {
        console.log(e)
        console.log('FAILURE!!');
      }).finally(() => {

        if (!this.showCropper) {
          this.setFileInputToNull()
        }

        this.promises = [];
        this.loading = false;

        this.discardCroppedImage();
      });
    },
    getFormData(file, name = null) {
      let formData = new FormData();

      if (name) {
        formData.append('file', file, name);
      } else {
        formData.append('file', file);
      }

      return formData;
    },
    uploadFilePromise(formData) {
      return axios.post('/admin/files/upload-single-temp-file', formData, {headers: {'Content-Type': 'multipart/form-data'}});
    },
    setFileInputToNull() {
      this.$refs.fileUploader.value = null;
    },
    deleteTempFile(index) {
      if (this.files[index]) {
        this.removedFiles[this.files[index].type].push(this.files[index].id);
        this.files.splice(index, 1);
      }
    },
    deleteAllFiles() {

      this.files.forEach((image) => {
        this.removedFiles[image.type].push(image.id);
      });

      this.files = [];
    }
  },
  mounted() {
    if (this.classId && this.className) {

      let data = {class_name: this.className, class_id: this.classId, collection: this.name};

      if (this.translatable) {
        data['lang'] = this.lang;
      }

      axios.get("/admin/files/get-media", {params: data}).then((response) => {
        this.files = this.files.concat(response.data);
      }).then(() => {
        this.files = this.files.filter((image) => {
          return !this.removedFiles.media.includes(image.id)
        })
      })
    }

    if (this.oldValue) {
      axios.get('/admin/files/get-temp-files', {params: {ids: this.oldValue.temp}}).then((response) => {
        this.files = this.files.concat(response.data);
      })

      this.removedFiles = this.oldValue.removedFiles
    }
  }
}
</script>

<style scoped lang="scss">
.gallery-upload-wrapper {

  width: 100%;

  .gallery-upload {

    input[type="file"] {
      display: none;
    }

    cursor: pointer;
  }
}

.delete-link {
  position: absolute;
  font-size: 20px;
  display: none;
}

.square {
  position: relative;
  width: 100%;

  &:after {
    content: "";
    display: block;
    padding-bottom: 100%;
  }

  .content {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    background: #eaecf1;
    border-radius: 3px;

    .delete-link {
      top: 1px;
      right: 3px;
    }

    &:hover {
      .delete-link {
        display: inline-block;
      }
    }
  }
}

.delete-all-link {
  font-weight: 600;
}

.preview-image {
  width: auto;
  height: auto;
  max-width: 100%;
  max-height: 100%;
}

.file-item {
  padding: 10px;
  background: #eaecf1;
  border-radius: 3px;
  width: 100%;
  margin-bottom: 5px;

  &:last-child {
    margin-bottom: 15px;
  }

  &:hover {
    .delete-link {
      display: inline-block;
    }
  }

  .delete-link {
    top: 50%;
    transform: translateY(-50%);
    right: 10px;
  }
}

</style>
