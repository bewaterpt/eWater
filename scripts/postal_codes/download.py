#!/usr/bin/python
# -*- coding: utf-8 -*-

'''\nScraper para o ficheiro de texto dos c\xc3\xb3digos postais dos CTT\nCopyleft (c) 2018 Ricardo Lafuente\n\nUsa um browser automatizado para fazer login e aceder ao ficheiro que queremos.\n'''

import os
import splinter
from selenium import webdriver
from splinter.driver.webdriver import BaseWebDriver, WebDriverElement
from selenium.webdriver import Chrome
from selenium.webdriver.chrome.options import Options
from time import sleep
from configparser import ConfigParser


def init_browser(webdriver='chrome', options=Options()):
    if webdriver == 'chrome':
        return splinter.Browser(webdriver, options=options,
                                service_log_path=os.path.devnull,
                                user_agent='Mozilla/5.0 ;Windows NT 6.1; WOW64; Trident/7.0; rv:11.0; like Gecko'
                                )
    else:
        return splinter.Browser(webdriver,
                                user_agent='Mozilla/5.0 ;Windows NT 6.1; WOW64; Trident/7.0; rv:11.0; like Gecko'
                                )  # headless=headless,


def run():
    config = ConfigParser()
    config.read(r'./credentials.ini')
    username = config.get('main', 'username')
    password = config.get('main', 'password')

    options = Options()
    options.add_argument('--no-sandbox')
    options.add_argument('--headless')
    options.add_argument('--disable-dev-shm-usage')

    browser = init_browser('chrome', options)
    browser.driver = Chrome(options=options)

    # em modo headless, temos de fazer isto para ele conseguir fazer o download
    # https://bugs.chromium.org/p/chromium/issues/detail?id=696481#c80

    browser.driver.command_executor._commands['send_command'] = ('POST'
            , '/session/$sessionId/chromium/send_command')
    params = {'cmd': 'Page.setDownloadBehavior',
              'params': {'behavior': 'allow', 'downloadPath': '.'}}
    browser.driver.execute('send_command', params)

    # Fazer o login

    browser.visit('https://www.ctt.pt/fecas/login')
    if browser.find_by_id('cookie-warning'):
        browser.find_by_id('cookie-warning').first.find_by_css('.close').first.click()

    browser.find_by_id('username').first.fill(username)
    browser.find_by_id('password').first.fill(password)
    browser.find_by_name('submit').first.click()

    # Ir ao URL do ficheiro

    browser.visit('https://www.ctt.pt/feapl_2/app/restricted/postalCodeSearch/postalCodeDownloadFiles!downloadPostalCodeFile.jspx'
                  )
    timer = 0
    while not os.path.exists('todos_cp.zip') and timer < 30:
        sleep(1)
        timer += 1
    browser.quit()


if __name__ == '__main__':
    run()

