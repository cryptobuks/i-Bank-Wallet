# 系统要求

Operating System:
    Production: CentOS or RedHat Enterprise Linux (latest release) or Ubuntu (16.04+) supported
    Development: Mac OS X, Windows (64-bit), or most Linux distributions
CPU: 64-bit x86_64, 2+ cores
Disk: Minimum 50GB for the database partition. SSD strongly recommended (minimum 1000 IOPS, more is better)
RAM: 8GB+

详细： https://developers.ripple.com/system-requirements.html

# Install on CentOS/Red Hat with yum

Installation Steps
1. Install the Ripple RPM repository:

  sudo rpm -Uvh https://mirrors.ripple.com/ripple-repo-el7.rpm
2. Install the rippled software package:

  sudo yum install --enablerepo=ripple-stable rippled
3. Configure the rippled service to start on system boot:

  sudo systemctl enable rippled.service
4. Start the rippled service

  sudo systemctl start rippled.service

# 默认配置文件

/opt/ripple/etc/rippled.cfg

# ubuntu 安装参考

https://developers.ripple.com/install-rippled-on-ubuntu-with-alien.html

