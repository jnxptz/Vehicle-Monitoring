<div class="user-profile" onclick="toggleProfileDropdown(event)">
    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
    <div class="user-info">
        <span class="user-name">{{ auth()->user()->name }}</span>
        <span class="user-role">{{ auth()->user()->role }}</span>
    </div>
    <svg class="profile-chevron" viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;"><path d="M6 9l6 6 6-6"/></svg>
    <div class="profile-dropdown" id="profileDropdown">
        <a href="{{ route('profile.settings') }}" class="profile-dropdown-item">
            <svg viewBox="0 0 24 24"><path d="M12.22 2h-.44a2 2 0 00-2 2v.18a2 2 0 01-1 1.73l-.43.25a2 2 0 01-2 0l-.15-.08a2 2 0 00-2.73.73l-.22.38a2 2 0 00.73 2.73l.15.1a2 2 0 011 1.72v.51a2 2 0 01-1 1.74l-.15.09a2 2 0 00-.73 2.73l.22.38a2 2 0 002.73.73l.15-.08a2 2 0 012 0l.43.25a2 2 0 011 1.73V20a2 2 0 002 2h.44a2 2 0 002-2v-.18a2 2 0 011-1.73l.43-.25a2 2 0 012 0l.15.08a2 2 0 002.73-.73l.22-.39a2 2 0 00-.73-2.73l-.15-.08a2 2 0 01-1-1.74v-.5a2 2 0 011-1.74l.15-.09a2 2 0 00.73-2.73l-.22-.38a2 2 0 00-2.73-.73l-.15.08a2 2 0 01-2 0l-.43-.25a2 2 0 01-1-1.73V4a2 2 0 00-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            Settings
        </a>
    </div>
</div>
<script>
function toggleProfileDropdown(event){event.stopPropagation();document.getElementById('profileDropdown').classList.toggle('show');}
document.addEventListener('click',function(){var d=document.getElementById('profileDropdown');if(d)d.classList.remove('show');});
</script>
