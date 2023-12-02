FROM ngrok/ngrok:latest

COPY ngrok.yml /etc/ngrok.yml

USER root

ARG NGROK_AUTHTOKEN
ARG NGROK_DOMAIN
RUN sed -i 's/$NGROK_AUTHTOKEN/'$NGROK_AUTHTOKEN'/g' /etc/ngrok.yml
RUN sed -i 's/$NGROK_DOMAIN/'$NGROK_DOMAIN'/g' /etc/ngrok.yml

#CMD ["ngrok", "start", "--all", "--config", "/etc/ngrok.yml"]
