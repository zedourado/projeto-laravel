console.log("Está OK!");

document.addEventListener('DOMContentLoaded', function () {
    const notificationDropdown = document.getElementById('notificationsDropdown');
    const notificationMenu = document.getElementById('notificationsDropdownMenu');
    const notificationCount = document.getElementById('notificationCount');

    if (!notificationDropdown) return;

    notificationDropdown.addEventListener('click', function () {
        // 1. Carregar notificações
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                notificationMenu.innerHTML = '';

                if(data.length === 0){
                    const li = document.createElement('li');
                    li.innerHTML = '<p class="text-center mb-0">Sem notificações</p>';
                    notificationMenu.appendChild(li);
                    notificationCount.style.display = 'none';
                    return;
                }

                data.forEach(notif => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <div class="card p-2 mb-2 ${notif.read ? 'bg-light' : 'bg-white'}" style="width: 100%; border-radius: 10px;">
                            <strong>${notif.title}</strong>
                            <p class="mb-1">${notif.message}</p>
                            <small class="text-muted">${new Date(notif.created_at).toLocaleString()}</small>
                        </div>
                    `;
                    notificationMenu.appendChild(li);
                });

                // 2. Marcar notificações como lidas
                fetch('/notifications/mark-as-read', { 
                    method: 'POST', 
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    notificationCount.style.display = 'none';
                });
            });
    });
});
