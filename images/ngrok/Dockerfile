FROM ngrok/ngrok:alpine

COPY ./ngrok.yml /etc/ngrok.yml

USER root

ARG NGROK_AUTHTOKEN
RUN sed -i 's/{{ NGROK_AUTHTOKEN }}/'$NGROK_AUTHTOKEN'/g' /etc/ngrok.yml
