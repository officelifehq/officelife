describe('Project - project creation', function () {
  it('should let an employee create a project as administrator', function () {
    cy.login()

    cy.createCompany()

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1')
    cy.url().should('include', '/1/projects/1')

    // make sure we can create a project name + code
    cy.createProject(1, 'project 2', 'code project 2')
    cy.url().should('include', '/1/projects/2')

    // make sure we can create a project name + code + summary
    cy.createProject(1, 'project 3', 'code project 3', 'summary project 3')
    cy.url().should('include', '/1/projects/3')

    // make sure we can create a project name + code + summary + project lead
    cy.createProject(1, 'project 4', 'code project 4', 'summary project 4', 1)
    cy.url().should('include', '/1/projects/4')
  })

  it('should create a project as hr', function () {
    cy.login()

    cy.createCompany()
    cy.changePermission(1, 200)

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1')
    cy.url().should('include', '/1/projects/1')

    // make sure we can create a project name + code
    cy.createProject(1, 'project 2', 'code project 2')
    cy.url().should('include', '/1/projects/2')

    // make sure we can create a project name + code + summary
    cy.createProject(1, 'project 3', 'code project 3', 'summary project 3')
    cy.url().should('include', '/1/projects/3')

    // make sure we can create a project name + code + summary + project lead
    cy.createProject(1, 'project 4', 'code project 4', 'summary project 4', 1)
    cy.url().should('include', '/1/projects/4')
  })

  it('should create a project as normal user', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 300)

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1')
    cy.url().should('include', '/1/projects/1')

    // make sure we can create a project name + code
    cy.createProject(1, 'project 2', 'code project 2')
    cy.url().should('include', '/1/projects/2')

    // make sure we can create a project name + code + summary
    cy.createProject(1, 'project 3', 'code project 3', 'summary project 3')
    cy.url().should('include', '/1/projects/3')

    // make sure we can create a project name + code + summary + project lead
    cy.createProject(1, 'project 4', 'code project 4', 'summary project 4', 1)
    cy.url().should('include', '/1/projects/4')
  })
})
