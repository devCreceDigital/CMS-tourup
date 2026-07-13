(function () {
  'use strict';

  /* ===================================================================
     Dashboard — Main JavaScript
     No var, no innerHTML, no alert/confirm/prompt
     =================================================================== */

  /* ----- Sidebar Toggle (Mobile) ----- */
  const sidebar = document.querySelector('.sidebar');
  const sidebarToggle = document.querySelector('.sidebar-toggle');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('sidebar--open');
      const isOpen = sidebar.classList.contains('sidebar--open');
      sidebarToggle.setAttribute('aria-expanded', isOpen);
    });
  }

  /* ----- Auto-generate Slug from Title ----- */
  const titleInput = document.querySelector('[data-slug-source]');
  const slugInput = document.querySelector('[data-slug-target]');

  if (titleInput && slugInput) {
    let slugEdited = false;

    slugInput.addEventListener('input', function () {
      slugEdited = slugInput.value.length > 0;
    });

    titleInput.addEventListener('input', function () {
      if (slugEdited) return;
      const slug = titleInput.value
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_]+/g, '-')
        .replace(/--+/g, '-')
        .replace(/^-+|-+$/g, '');
      slugInput.value = slug;
    });
  }

  /* ----- Form Confirmation Dialogs ----- */
  document.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-confirm]');
    if (!trigger) return;

    e.preventDefault();
    const message = trigger.getAttribute('data-confirm') || '¿Estás seguro de realizar esta acción?';

    const overlay = document.createElement('div');
    overlay.className = 'confirm-overlay';
    Object.assign(overlay.style, {
      position: 'fixed',
      inset: '0',
      background: 'rgba(0,0,0,0.4)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      zIndex: '9999',
    });

    const dialog = document.createElement('div');
    dialog.className = 'confirm-dialog';
    Object.assign(dialog.style, {
      background: '#fff',
      borderRadius: '0.75rem',
      padding: '2.4rem',
      maxWidth: '40rem',
      width: '90%',
      boxShadow: '0 20px 60px rgba(0,0,0,0.15)',
      fontFamily: 'Inter, sans-serif',
    });

    const text = document.createElement('p');
    text.textContent = message;
    Object.assign(text.style, {
      margin: '0 0 2rem',
      fontSize: '1.5rem',
      lineHeight: '1.5',
      color: '#1C1C1A',
    });

    const actions = document.createElement('div');
    Object.assign(actions.style, {
      display: 'flex',
      gap: '1rem',
      justifyContent: 'flex-end',
    });

    const btnCancel = document.createElement('button');
    btnCancel.textContent = 'Cancelar';
    Object.assign(btnCancel.style, {
      padding: '0.8rem 1.8rem',
      borderRadius: '0.5rem',
      border: '1px solid #E2E8F0',
      background: '#fff',
      cursor: 'pointer',
      fontSize: '1.4rem',
      fontWeight: '500',
    });

    const btnConfirm = document.createElement('button');
    btnConfirm.textContent = 'Confirmar';
    Object.assign(btnConfirm.style, {
      padding: '0.8rem 1.8rem',
      borderRadius: '0.5rem',
      border: 'none',
      background: '#2563EB',
      color: '#fff',
      cursor: 'pointer',
      fontSize: '1.4rem',
      fontWeight: '600',
    });

    actions.appendChild(btnCancel);
    actions.appendChild(btnConfirm);
    dialog.appendChild(text);
    dialog.appendChild(actions);
    overlay.appendChild(dialog);
    document.body.appendChild(overlay);

    function close() {
      document.body.removeChild(overlay);
    }

    btnCancel.addEventListener('click', close);
    overlay.addEventListener('click', function (ev) {
      if (ev.target === overlay) close();
    });

    btnConfirm.addEventListener('click', function () {
      close();
      const tag = trigger.tagName.toLowerCase();
      if (tag === 'a' || tag === 'button') {
        const form = trigger.closest('form');
        if (form) {
          const hidden = document.createElement('input');
          hidden.type = 'hidden';
          hidden.name = trigger.getAttribute('name') || '_action';
          hidden.value = trigger.getAttribute('value') || 'delete';
          form.appendChild(hidden);
          form.submit();
        } else if (tag === 'a') {
          const href = trigger.getAttribute('href');
          if (href && href !== '#') {
            window.location.href = href;
          }
        }
      }
    });
  });

  /* ----- Table Search Filter (Client-side) ----- */
  const searchInput = document.querySelector('[data-table-search]');

  if (searchInput) {
    const tableId = searchInput.getAttribute('data-table-search');
    const table = document.getElementById(tableId);
    if (table) {
      searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase().trim();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(function (row) {
          let match = false;
          const cells = row.querySelectorAll('td');
          cells.forEach(function (cell) {
            if (cell.textContent.toLowerCase().includes(query)) {
              match = true;
            }
          });
          row.style.display = match ? '' : 'none';
        });
      });
    }
  }

  /* ----- Status Badge Animations ----- */
  document.querySelectorAll('[data-status]').forEach(function (badge) {
    badge.classList.add('status-badge--animating');
  });

  /* ----- Notification Dropdown Toggle ----- */
  const notifTrigger = document.querySelector('[data-notif-toggle]');
  const notifDropdown = document.querySelector('[data-notif-dropdown]');

  if (notifTrigger && notifDropdown) {
    notifTrigger.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = notifDropdown.classList.contains('dropdown--open');
      closeAllDropdowns();
      if (!isOpen) {
        notifDropdown.classList.add('dropdown--open');
        notifTrigger.setAttribute('aria-expanded', 'true');
      }
    });
  }

  /* ----- User Dropdown Toggle ----- */
  const userTrigger = document.querySelector('[data-user-toggle]');
  const userDropdown = document.querySelector('[data-user-dropdown]');

  if (userTrigger && userDropdown) {
    userTrigger.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = userDropdown.classList.contains('dropdown--open');
      closeAllDropdowns();
      if (!isOpen) {
        userDropdown.classList.add('dropdown--open');
        userTrigger.setAttribute('aria-expanded', 'true');
      }
    });
  }

  /* ----- Close Dropdowns on Click Outside ----- */
  function closeAllDropdowns() {
    document.querySelectorAll('.dropdown--open').forEach(function (dd) {
      dd.classList.remove('dropdown--open');
    });
    document.querySelectorAll('[aria-expanded="true"]').forEach(function (el) {
      el.setAttribute('aria-expanded', 'false');
    });
  }

  document.addEventListener('click', function () {
    closeAllDropdowns();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeAllDropdowns();
    }
  });

  /* ----- Trip Filter Handling ----- */
  const filterForms = document.querySelectorAll('[data-filter-form]');
  filterForms.forEach(function (form) {
    const inputs = form.querySelectorAll('select, input[type="checkbox"], input[type="radio"]');
    inputs.forEach(function (input) {
      input.addEventListener('change', function () {
        form.submit();
      });
    });
  });

  /* ----- Trip Filter -- Quick Status Tabs ----- */
  const filterTabs = document.querySelectorAll('[data-filter-tab]');
  filterTabs.forEach(function (tab) {
    tab.addEventListener('click', function (e) {
      e.preventDefault();
      filterTabs.forEach(function (t) {
        t.classList.remove('filter-tab--active');
      });
      tab.classList.add('filter-tab--active');
      const status = tab.getAttribute('data-filter-tab');
      const targetContainer = document.querySelector(tab.getAttribute('data-target') || '[data-filter-results]');
      if (!targetContainer) return;
      const items = targetContainer.querySelectorAll('[data-filter-item]');
      items.forEach(function (item) {
        if (status === 'all' || item.getAttribute('data-filter-item') === status) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });

})();
