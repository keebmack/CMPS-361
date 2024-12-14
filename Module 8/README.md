# Steelers History Chatbot

## Overview
The Steelers History Chatbot is a web application that answers user queries about the history of the Pittsburgh Steelers. It supports 10 predefined questions and provides a fallback response for unrecognized queries.

## Features
- Accepts user input via a text box or Enter key.
- Provides intelligent responses based on a PostgreSQL database.
- Displays a Steelers-themed interface.
- Includes a welcome popup message: "Here we go Steelers, here we go!"

## Technology Stack
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** PostgreSQL

## Database Schema
CREATE TABLE chatbot_responses (
    id SERIAL PRIMARY KEY,
    question TEXT NOT NULL,
    response TEXT NOT NULL
);

INSERT INTO chatbot_responses (question, response)
VALUES
    ('Who is the Steelers all-time passing yards leader?', 'Ben Roethlisberger'),
    ('Who is the Steelers all-time leading rusher?', 'Franco Harris'),
    ('What is the Steelers nickname for their defensive line in the 1970s?', 'The Steel Curtain'),
    ('Who is the Steelers head coach?', 'Mike Tomlin'),
    ('How many Super Bowls have the Steelers won?', '6, soon to be 7'),
    ('Who is the Steelers all-time leading receiver?', 'Hines Ward'),
    ('What is the name of the Steelers home stadium?', 'Acrisure Stadium.'),
    ('When were the Steelers founded?', '1933'),
    ('What are the Steelers team colors?', 'black and gold'),
    ('Who is the Steelers mascot?', 'Steely McBeam');