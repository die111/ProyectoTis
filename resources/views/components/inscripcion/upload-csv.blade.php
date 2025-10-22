<div class="upload-inline mb-6">
  <label for="csvUpload" class="upload-label">Subir CSV*:</label>
  <div id="fakeFile" class="fake-file" title="Seleccionar archivo CSV">
    <span id="fileName" class="file-name">Ningún archivo seleccionado</span>
    <svg class="ico ico-18 upload-ico" viewBox="0 0 24 24" aria-hidden="true">
      <path d="M12 16V4M12 4l-4 4m4-4 4 4M4 20h16" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>
  <input id="csvUpload" type="file" accept=".csv" class="hidden-file"/>
  <button id="btnUpload"
          class="px-4 py-2 rounded-md bg-[#091c47] text-white text-sm font-medium hover:bg-[#0c3e92]"
          disabled>
    Subir
  </button>
</div>