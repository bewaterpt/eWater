from paramiko import SSHClient
from scp import SCPClient

ssh = SSHClient()
ssh.load_system_host_keys()
ssh.connect(hostname='ftp.bewater.com.pt',
            port='999',
            username='ourem',
            password='bewater')

scp = SCPClient(ssh.get_transport())
scp.put('/var/www/apps/eWater/storage/app/interruptions/comunicados.xls', '/')
