from paramiko import SSHClient
from scp import SCPClient

ssh = SSHClient()
ssh.load_system_host_keys()
ssh.get_host_keys().add('ftp.bewater.com.pt', 'ssh-rsa', "SHA256:pEpWtb90dTZAofgJfg6qa9ZbRrn/ONe6jyr2wVWUxEU")
ssh.connect(hostname='ftp.bewater.com.pt',
            port='999',
            username='ourem',
            password='bewater')

scp = SCPClient(ssh.get_transport())
scp.put('/var/www/apps/eWater/storage/app/interruptions/comunicados.xls', '/')
