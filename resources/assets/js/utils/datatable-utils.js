/**
 * Aplica clases personalizadas a los elementos de DataTable
 */
export function applyCustomClasses() {
  const elementsToModify = [
    { selector: '.dt-buttons .btn', classToRemove: 'btn-secondary' },
    { selector: '.dt-search', classToAdd: 'me-4' },
    { selector: '.dt-search .form-control', classToRemove: 'form-control-sm' },
    { selector: '.dt-length', classToAdd: 'mb-0 mb-md-5' },
    { selector: '.dt-length .form-select', classToRemove: 'form-select-sm' },
    { selector: '.dt-buttons', classToAdd: 'mb-0 w-auto' },
    { selector: '.dt-layout-start', classToAdd: 'mt-0 px-5' },
    {
      selector: '.dt-layout-end',
      classToRemove: 'justify-content-between',
      classToAdd: 'justify-content-md-between justify-content-center d-flex flex-wrap gap-md-4 mb-sm-0 mb-6 mt-0'
    },
    { selector: '.dt-layout-start', classToAdd: 'mt-0' },
    { selector: '.dt-layout-table', classToRemove: 'row mt-2' },
    { selector: '.dt-layout-full', classToRemove: 'col-md col-12', classToAdd: 'table-responsive' }
  ];

  elementsToModify.forEach(({ selector, classToRemove, classToAdd }) => {
    document.querySelectorAll(selector).forEach(element => {
      if (classToRemove) {
        classToRemove.split(' ').forEach(c => element.classList.remove(c));
      }
      if (classToAdd) {
        classToAdd.split(' ').forEach(c => element.classList.add(c));
      }
    });
  });
}

/**
 * Genera un layout de DataTable con buscador y botón custom
 * @param {string} modalId - ID del modal de Bootstrap (#exampleModal)
 * @param {string} buttonText - Texto del botón
 * @param {Array<number>} exportColumns - Índices de columnas a exportar
 * @returns {object} Configuración para layout
 */
export function createDataTableLayout(modalId, buttonText, exportColumns = []) {
  return {
    topEnd: {
      features: [
        {
          search: {
            placeholder: 'Search Permissions',
            text: '_INPUT_',
          }
        },
        {
          buttons: [
            {
              extend: 'collection',
              className: 'btn btn-label-secondary dropdown-toggle me-4',
              text: '<span class="d-flex align-items-center gap-1"><i class="icon-base ti tabler-upload icon-xs"></i> <span class="d-inline-block">Export</span></span>',
              buttons: [
                {
                  extend: 'print',
                  text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-printer me-1"></i>Print</span>`,
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: exportColumns
                  },
                  customize: function (win) {
                    win.document.body.style.color = config.colors.headingColor;
                    win.document.body.style.borderColor = config.colors.borderColor;
                    win.document.body.style.backgroundColor = config.colors.bodyBg;
                    const table = win.document.body.querySelector('table');
                    table.classList.add('compact');
                    table.style.color = 'inherit';
                    table.style.borderColor = 'inherit';
                    table.style.backgroundColor = 'inherit';
                  }
                },
                {
                  extend: 'csv',
                  text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file me-1"></i>Csv</span>`,
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: exportColumns
                  }
                },
                {
                  extend: 'excel',
                  text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-export me-1"></i>Excel</span>`,
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: exportColumns
                  }
                },
                {
                  extend: 'pdf',
                  text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-text me-1"></i>Pdf</span>`,
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: exportColumns
                  }
                },
                {
                  extend: 'copy',
                  text: `<i class="icon-base ti tabler-copy me-1"></i>Copy`,
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: exportColumns
                  }
                }
              ]
            },
            {
              text: `<i class="icon-base ti tabler-plus icon-xs me-0 me-sm-2"></i>
                                   <span class="d-none d-sm-inline-block">${buttonText}</span>`,
              className: 'add-new btn btn-primary',
              attr: {
                'data-bs-toggle': 'modal',
                'data-bs-target': `#${modalId}`
              }
            }
          ]
        }
      ]
    }
  };
}