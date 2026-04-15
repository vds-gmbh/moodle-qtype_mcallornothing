All-or-Nothing Multiple Choice Question Type
=============================================

A multiple-choice, multiple-response question type for Moodle 4.5+.

Based on qtype_multichoiceset, originally created by Adriane Boyd,
later maintained by Jean-Michel Vedrine and Eoin Campbell.

### Description

The All-or-Nothing question is adapted from the existing multichoice question.
The main difference from the standard Moodle multiple choice question type is
in the way that grading works.
The teacher editing interface is slightly modified as when creating the question,
the teacher just indicates which choices are correct.

### Grading

In an All-or-Nothing multiple choice question, a respondent can choose one or more answers.
If the chosen answers correspond exactly to the correct choices defined in the question,
the respondent gets 100%. If he/she chooses any incorrect choices or does not select all
of the correct choices, the grade is 0%.

### Installation

Install into `question/type/mcallornothing` and visit the admin notifications page.
